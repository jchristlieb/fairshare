<?php

namespace App\Controller;

use App\Model\User;

class IndexController extends Controller
{
    public function index()
    {

        echo $this->twig->render('/pages/dashboard.twig', ['name' => 'World']);
    }
}