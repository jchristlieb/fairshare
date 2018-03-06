<?php

namespace App\Controller;

class CalcController extends Controller
{
    public function index()
    {
        echo $this->twig->render('/pages/calc-1.twig', ['name' => 'World']);
    }
}