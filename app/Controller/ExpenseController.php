<?php

namespace App\Controller;

class ExpenseController extends Controller
{
    public function index()
    {
        echo $this->twig->render('/pages/expense.twig', ['name' => 'World']);
    }
}