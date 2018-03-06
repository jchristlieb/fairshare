<?php

namespace App\Controller;

class IndexController extends Controller 
{
    public function index()
    {
        echo $this->twig->render('/pages/dashboard.twig', ['name' => 'World']);
    }
}