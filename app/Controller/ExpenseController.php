<?php

namespace App\Controller;

use App\Library\SessionManager;
use App\Model\Expense;
use App\Model\User;

class ExpenseController extends Controller
{
    public function index()
    {

        echo $this->twig->render('/pages/expense.twig', ['users' => User::all()]);
    }

    public function save()
    {

        // create a new object expense and assign the user data to the attributes
      $expense = new Expense();
      $expense->user_id = $_POST['user'];
      $expense->amount = $_POST['amount'];
      $expense->shop = $_POST['shop'];
      $expense->expense_date = $this->parseDate($_POST['date']);
      $expense->save();

      // render view
        echo $this->twig->render('/feedback/expense-submitted.twig', [
            'expense' => Expense::returnLatestEntry(),
            'user' => User::findById($_POST['user'])
        ]);
    }

    protected function parseDate($date){
        return date("Y-m-d H:i:s", strtotime($date));
    }
}