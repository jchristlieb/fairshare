<?php

namespace App\Controller;

use App\Model\User;

class CalcController extends Controller
{
    public function selectUser()
    {
        echo $this->twig->render('/pages/calc-1.twig', ['users' => User::all()]);
    }

    public function selectTimeFrame()
    {
        echo $this->twig->render('/pages/calc-2.twig');
    }

    public function selectDaysOff()
    {
        echo $this->twig->render('/pages/calc-3.twig');
    }

    public function calcFairShare()
    {
        echo $this->twig->render('/pages/calc-4.twig');
    }
}