<?php

namespace App\Controller;

use App\Model\User;

class IndexController extends Controller
{
    public function index()
    {
        $user = User::findBy('name', 'jan');

        var_dump($user);
        die();
        $user = User::findById(4);

        var_dump($user->id);
        die();
        echo $this->twig->render('/pages/dashboard.twig', ['name' => 'World']);
    }
}