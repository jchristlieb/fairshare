<?php

namespace App\Controller;

use App\Library\ExpenseCalculator;
use App\Model\Expense;
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
        $selectedUsers = $session->get('selected_users');

        // get start and end date from session
        $start = $session->get('start_date');
        $end = $session->get('end_date');

        // get days off from session
        $daysOff = $session->get('days_off');

        // start new instance of expense calculator
        $expenseCalculator = new ExpenseCalculator();

        // assign variables to expense calculator
        $expenseCalculator
            ->setStartDate($start)
            ->setEndDate($end)
            ->setUsersById($selectedUsers, $daysOff);

        // get days of time frame
        $totalDays = $expenseCalculator->getTimeFrame();

        // cost per day = total expenses / total days
        $expenseCalculator->calculateCostPerDay();

        // cost per user = cost per day * user days
        $expenseCalculator->calculateCostPerUser();
        $expenseCalculator->sortUsers();
        dump($expenseCalculator);

        $expenseCalculator->calculatePaymentPerUser();
        dump($expenseCalculator);
        // get object for all users
        $users = $expenseCalculator->getUsers();

        // get total amount of expenses
        $totalAmount = $expenseCalculator->getTotalAmount();

        // get cost per day
        $costPerDay = $expenseCalculator->getCostPerDay();

        // get number of users
        $nrOfUsers = count($users);

        echo $this->twig->render('/pages/calc-4.twig', [
            'users' => $users,
            'total_days' => $totalDays,
            'cost_per_day' => $costPerDay,
            'start' => $start,
            'end' => $end,
            'total_amount' => $totalAmount,
            'nr_users' => $nrOfUsers,
        ]);
    }
}