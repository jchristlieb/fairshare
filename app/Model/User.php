<?php

namespace App\Model;

use Faker\Factory;

class User extends Model
{
    public function getSource()
    {
        return 'users';
    }

    public static function createFakes($count = 10){
        $faker = Factory::create();
        for ($i = 0; $i <= $count; $i++){
            $user = new User();
            $user->name = $faker->name;
            $user->email = $faker->email;
            $user->password = $faker->password;
            $user->save();
        }
    }
}