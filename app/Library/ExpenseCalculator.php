<?php

namespace App\Library;

use App\Model\Expense;
use App\Model\User;

class ExpenseCalculator
{
    protected $startDate;
    protected $endDate;
    protected $users;
    protected $totalAmount = 0;
    protected $costPerDay;


    public function getStartDate()
    {
        return $this->startDate;
    }

    // setter for stat date of calculation
    public function setStartDate($date)
    {
        $this->startDate = $date;

        return $this;
    }

    // setter for end date of calculation
    public function setEndDate($date)
    {
        $this->endDate = $date;

        return $this;
    }

    // get data from database per user
    public function setUsersById($userIds, $daysOff)
    {
        foreach ($userIds as $id) {

            // get user object
            $user = User::findById($id);

            $user->setExpenses(Expense::between($this->startDate, $this->endDate, $user));
            // set totalAmount to the user object
            $user->calculateTotalAmount();

            // set daysOff to the user object
            $user->setDaysOff($daysOff[$id]);

            // build a new array with information on user basis
            $this->users[] = $user;
        }

        return $this;
    }

    public function calculateCostPerDay()
    {
        $totalExpenses = 0;
        foreach ($this->users as $user){
            /** @var $user User */
            $totalExpenses += $user->getTotalAmount();
        }

        $this->totalAmount = $totalExpenses;
        $this->costPerDay = round($totalExpenses/$this->getTotalDays(),2);
    }


    // cost per user = cost per day * user days
    public function calculateCostPerUser()
    {
        // foreach auf array
        foreach ($this->users as $user) {
            /** @var $user User */
            $user->setActualDays($this->getTimeFrame() - $user->getDaysOff());
            $user->setActualCosts((int)($this->costPerDay * $user->getActualDays()));
            $user->setBudget((int)($user->getTotalAmount() - $user->getActualCosts()));
            $user->setBalance($user->getBudget());
        }
    }

    public function getTimeFrame()
    {
        $timeFrame = round((strtotime($this->endDate) - strtotime($this->startDate)) / (24 * 60 * 60), 0);

        return (int)$timeFrame;
    }


    protected function getTotalDays()
    {
        $totalDays = 0;

        foreach ($this->users as $user) {
            /** @var $user User */
            $totalDays += ($this->getTimeFrame() - $user->getDaysOff());
        }

        return $totalDays;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    public function getCostPerDay()
    {
        return $this->costPerDay;
    }

    public function calculatePaymentPerUser()
    {
        // 1. create a new array payments to the user object check

        // 2. sort all user objects according to their balance value, in descending order check

        // 3. take lowest balance and subtract it from highest balance. but max the amount that the highest balance goes to zero
        while ($this->checkWhileCondition()){
            $payment = new Payment();
            /** @var User $fromUser */
            $fromKey = count($this->users) -1;

            $payment->setFrom($this->users[$fromKey]);
            $payment->setTo($this->users[0]);
            $fromBudget = abs($payment->getFrom()->getBudget());
            $toBudget = $payment->getTo()->getBudget();

            if($toBudget >= $fromBudget){
                $payment->setAmount($fromBudget);
                $this->users[$fromKey]->setBudget(0);
                $this->users[0]->setBudget($toBudget - $fromBudget);
            }else{
                $payment->setAmount($toBudget);
                $this->users[$fromKey]->setBudget($this->users[$fromKey]->getBudget()+$toBudget);
                $this->users[0]->setBudget(0);
            }
            $this->users[$fromKey]->addPayment($payment);
            $this->sortUsers();
        }
        // 4. write into array 'payments' from the user with the lowest amount, the amount that was subtracted and the user_id from the other user

        // 5. repeat step 3. and 4. as long as all balances are < 1 and >= 0

    }

    protected function checkWhileCondition(){

        $budgets = [];
        foreach ($this->users as $user) {
            $budgets[] = $user->getBudget();
        }
        if(max($budgets) <= 5 && min($budgets) >= -5){
            return false;
        }

        return true;
    }


    public function sortUsers()
    {
        usort($this->users, function ($a, $b) {
            if($a->getBudget() == $b->getBudget()){
                return 0;
            }
            return ($a->getBudget() > $b->getBudget()) ? -1 : 1;
        });
    }
}