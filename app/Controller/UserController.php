<?php

namespace App\Controller;

use App\Model\User;

class UserController extends Controller
{
    public function index()
    {
        echo $this->twig->render('/pages/user.twig');
    }

    public function save()
    {
        // create a new object User
        $user = new User();

        // assign values from register user to User object
        $user->name = $_POST['user'];
        $user->email = $_POST['email'];
        $user->password = $_POST['password'];

        // save $user to database
        $user->save();

        // return
        echo $this->twig->render('/feedback/user-registered.twig', ['user' => User::findBy('email', $_POST['email'])]);
    }

}