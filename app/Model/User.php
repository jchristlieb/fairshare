<?php

namespace App\Model;

use App\Library\Payment;
use Faker\Factory;

class User extends Model
{
    /**
     * @var array
     */
    protected $expenses;

    /**
     * @var int
     */
    protected $totalAmount;

    /**
     * @var int
     */
    protected $daysOff;

    /**
     * @var int
     */
    protected $actualCosts;

    /**
     * @var int
     */
    protected $actualDays;

    /**
     * @var array
     */
    protected $payments;

    /**
     * @var int
     */
    protected $balance;

    /**
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param int $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }



    /**
     * @return array
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @param Payment $payment
     */
    public function addPayment($payment)
    {
        $this->payments[] = $payment;
    }


    /**
     * @return int
     */
    public function getActualCosts()
    {
        return $this->actualCosts;
    }

    /**
     * @param int $actualCosts
     */
    public function setActualCosts($actualCosts)
    {
        $this->actualCosts = $actualCosts;
    }

    /**
     * @return int
     */
    public function getActualDays()
    {
        return $this->actualDays;
    }

    /**
     * @param int $actualDays
     */
    public function setActualDays($actualDays)
    {
        $this->actualDays = $actualDays;
    }

    /**
     * @return int
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * @param int $budget
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
    }

    /**
     * @var int
     */
    protected $budget;

    /**
     * @return int
     */
    public function getDaysOff()
    {
        return $this->daysOff;
    }

    /**
     * @param int $daysOff
     */
    public function setDaysOff($daysOff)
    {
        $this->daysOff = $daysOff;
    }


    /**
     * @return int
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @param int $totalAmount
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }



    public function getSource()
    {
        return 'users';
    }


    public function setExpenses($expenses = null)
    {
        if(!$expenses){
            $this->expenses = Expense::findBy('user_id', $this->id , true);
        }else{
            $this->expenses = $expenses;
        }

        return $this;
    }

    public function getExpenses()
    {
        if (!$this->expenses) {
            $this->setExpenses();
        }
        return $this->expenses;
    }

    public static function createFakes($count = 10){
        $faker = Factory::create();
        for ($i = 0; $i <= $count; $i++){
            $user = new User();
            $user->name = $faker->name;
            $user->email = $faker->email;
            $user->password = $faker->password;
            $user->save();
        }
    }

    public function calculateTotalAmount()
    {
        foreach ($this->getExpenses() as $expense) {
            $this->totalAmount += $expense->amount;
        }

        return $this;
    }
}