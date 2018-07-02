<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 17.03.18
 * Time: 16:28
 */

namespace App\Library;


use App\Model\User;

class Payment
{
    /**
     * @var int
     */
    protected $amount;

    /**
     * @var User
     */
    protected $from;

    /**
     * @var User
     */
    protected $to;

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }
}