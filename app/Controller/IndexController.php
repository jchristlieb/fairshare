<?php

namespace App\Controller;

use App\Model\Expense;
use App\Model\User;

class IndexController extends Controller
{
    public function index()
    {
        //Expense::createFakes(20);
        //User::createFakes(10);
        echo $this->twig->render('/pages/dashboard.twig', ['name' => 'World']);
    }
}