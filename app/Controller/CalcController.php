<?php

namespace App\Controller;

use App\Model\User;
use App\Library\SessionManager;

class CalcController extends Controller
{

    public function selectUserGroup()
    {
        // render calc-1.twig and handover all user objects
        echo $this->twig->render('/pages/calc-1.twig', ['users' => User::all()]);
    }


    public function selectTimeFrame()
    {
        // start a new session
        $session = new SessionManager();

        // save selected users into session
        $session->set('selected_users', $_POST['selected_users']);
        var_dump($_SESSION);

        // render calc-2.twig
        echo $this->twig->render('/pages/calc-2.twig');
    }


    public function selectDaysOff()
    {
        // continue session
        $session = new SessionManager();

        // save selected start and end date into session
        $session->set('start_date', $_POST['start_date']);
        $session->set('end_date', $_POST['end_date']);

        var_dump($_SESSION);

        // get user_ids from session
        $user_ids = $session->get('selected_users');

        // query user data for all id's that are saved in user_group
        foreach ($user_ids as $user_id) {
            $user = User::findById($user_id);

            // save the query result into $user_group array
            $user_group[] = $user;
        }

        //render calc-3 and hand over object of all selected user
        echo $this->twig->render('/pages/calc-3.twig', ['users' => $user_group]);

    }


    public function calcFairShare()
    {
        // continue session
        $session = new SessionManager();

        // save days off per user into session
        $session->set('days_off', $_POST['days_off']);

        // get user ids from session
        $users = $session->get('selected_users');
        echo '<h2>All user ids</h2>';
        var_dump($users);

        // get start and end date from session
        $start = $session->get('start_date');
        $end = $session->get('end_date');

        echo '<h2>timeframe</h2>';
        $timeFrame = $this->getTimeFrame($start, $end);
        var_dump($timeFrame);

        echo '<h2>get all users with days off</h2>';
        $daysoff = $session->get('days_off');
        var_dump($daysoff);

        echo '<h2>all user objects</h2>';


        // make calculations with session data

        echo $this->twig->render('/pages/calc-4.twig');
    }


    protected function getTimeFrame($start, $end)
    {
        // make string to date format
        $startDate = strtotime($start);
        $endDate = strtotime($end);

        // subtract start- from end date
        // change unit from seconds to days
        // round to whole number
        $timeFrame = round( ($endDate - $startDate) / (24*60*60), 0);

        return $timeFrame;
    }
}