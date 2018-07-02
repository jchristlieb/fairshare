<?php

namespace App\Model;

abstract class Model
{
    /**
     * @var \PDO
     */
    private static $pdo;

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        if (self::$pdo === null) {
            self::$pdo = new \PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
        }
        return self::$pdo;
    }

    /**
     * returns the name of the mysql table used for the model
     *
     * @return string
     */
    abstract public function getSource();

    /**
     * returns an array of objects
     *
     * @return array
     */
    public static function all()
    {
        // erzeuge neue instanz der Klasse auf dem die statische Methode AUSGEFÜHRT wird
        $model = new static();
        // hole den namen der DB-tabelle
        $table = $model->getSource();
        // erstelle eine datenbankverbindung
        $pdo = $model->getPdo();
        // bereite den DB query vor
        $stmt = $pdo->prepare('SELECT * FROM `' . $table . '`');
        // execute it NOW
        $stmt->execute();
        // bekomme als Ergebnis ein array mit objekten der klasse auf der die statische methode ausgeführt wird -> the magic of pdo :-)
        return $stmt->fetchAll(\PDO::FETCH_CLASS, get_class($model));
    }


    public static function returnLatestEntry()
    {
        $model = new static();
        $table = $model->getSource();
        $pdo = $model->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM `' . $table . '`ORDER BY id DESC LIMIT 1');
        $stmt->execute();
        return $stmt->fetchObject(get_class($model));
    }

    /**
     * returns an object with the given id or false
     * @param $id
     * @return static
     */
    public static function findById($id)
    {
        $model = new static();
        $table = $model->getSource();
        $pdo = $model->getPdo();
        if ($id) {
            $stmt = $pdo->prepare('SELECT * FROM `' . $table . '` WHERE id = ? LIMIT 1');
            $stmt->execute([(int)$id]);
        } else {
            throw new \UnexpectedValueException('you need to pass an id');
        }
        return $stmt->fetchObject(get_class($model));
    }

    /**
     * returns an object with the given id or false
     * @return object
     */
    public static function findBy($columnName, $value, $multiple = false)
    {
        $model = new static();
        $table = $model->getSource();
        $pdo = $model->getPdo();

        if(!$multiple){
            $limit = ' LIMIT 1';
        } else {
            $limit = '';
        }
        $stmt = $pdo->prepare('SELECT * FROM `' . $table . '` WHERE ' . $columnName . ' = :value'.$limit);

        $stmt->bindParam(':value', $value);
        $stmt->execute();

        if(!$multiple){
            return $stmt->fetchObject(get_class($model));
        }
        return $stmt->fetchAll(\PDO::FETCH_CLASS, get_class($model));
    }


    /**
     * saves an object. if the object wasn't saved before it fills the id of the object with the mysql row id
     */
    public function save()
    {
        $table = $this->getSource();
        $pdo = $this->getPdo();
        if (method_exists($this, 'beforeSave')) {
            $this->beforeSave();
        }

        $fields = [];
        foreach ($this as $name => $val) {
            if ($val === null) {
                $fields[] = "`$name`=null";
            } elseif (is_int($val)) {
                $fields[] = "`$name`=" . $val;
            } else {
                $fields[] = "`$name`=" . $pdo->quote($val);
            }
        }
        // wenn this id nicht gesetzt ist, dann soll ein neuer datensatz erstellt werden. ansonsten einen vorhandenen aktualisieren
        if ($this->id === null) {
            if (!$pdo->exec('INSERT INTO `' . $table . '` SET ' . implode(',', $fields))) {
                throw new \RuntimeException('Could not create ' . get_class($this) . ': ' . $pdo->errorInfo()[2]);
            }
            // fill the id
            $this->id = $pdo->lastInsertId();
        } else {
            // update entry
            if ($pdo->exec('UPDATE `' . $table . '` SET ' . implode(',', $fields) . ' WHERE `id` = ' . ((int)$this->id)) === FALSE) {
                throw new \RuntimeException('Could not update ' . get_class($this) . ': ' . $pdo->errorInfo()[2]);
            }
        }
    }

    /**
     * delete the record
     */
    public function delete()
    {
        $table = $this->getSource();
        $pdo = $this->getPdo();
        if ($this->id) {
            $stmt = $pdo->prepare('DELETE FROM `' . $table . '` WHERE id = ? ');
            $stmt->execute([$this->id]);
        } else {
            throw new \UnexpectedValueException('this model has no id');
        }
    }
}