<?php

namespace App\Model;

use Faker\Factory;

class Expense extends Model
{
    public function getSource()
    {
        return 'expenses';
    }

    public static function createFakes($count = 20)
    {
        $faker = Factory::create();
        $shops = ['Blaumilchkanal', 'Trude Ruth und Goldammer', 'Kulturfabrik', 'Kumpelnest 3000', 'Cimdata Kantine'];
        for ($i = 0; $i <= $count; $i++)
        {
            $expense = new Expense();
            $expense->amount = $faker->numberBetween(5,60);
            $expense->shop = $shops[rand(0,4)];
            $expense->user_id = rand(1,10);
            $expense->expense_date = date("Y-m-d H:i:s", strtotime('02/' .rand(1,28).'/2018'));
            $expense->save();
        }

    }

    public static function between($startDate, $endDate, User $user = null)
    {
        $model = new static();
        $table = $model->getSource();
        $pdo = $model->getPdo();

        if(!$user){
            $stmt = $pdo->prepare('SELECT * FROM `' . $table . '` WHERE expense_date BETWEEN :start AND :end  ORDER BY expense_date ASC');
        }else{
            $stmt = $pdo->prepare('SELECT * FROM `' . $table . '` WHERE (expense_date BETWEEN :start AND :end) AND user_id = :user_id  ORDER BY expense_date ASC');
            $stmt->bindParam(':user_id', $user->id);
        }

            $stmt->bindParam(':start', $startDate);
            $stmt->bindParam(':end', $endDate);

        $stmt->execute();


        return $stmt->fetchAll(\PDO::FETCH_CLASS, get_class($model));
    }

}