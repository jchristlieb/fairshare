<?php

use App\Library\Router;

// require the composer autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// require bootstrap.php which contains some pre-defined constants
require_once dirname(__DIR__) . '/app/bootstrap.php';

Router::init();

// dashboard
Router::add('/', [\App\Controller\IndexController::class, 'index']);

// expense routes
Router::add('/expense', [\App\Controller\ExpenseController::class, 'index']);
Router::add('/expense/save', [\App\Controller\ExpenseController::class, 'save']);

// calc routes
Router::add('/calc-1', [\App\Controller\CalcController::class, 'selectUser']);
Router::add('/calc-2', [\App\Controller\CalcController::class, 'selectTimeFrame']);
Router::add('/calc-3', [\App\Controller\CalcController::class, 'selectDaysOff']);
Router::add('/calc-4', [\App\Controller\CalcController::class, 'calcFairShare']);



// user routes
Router::add('/user', [\App\Controller\UserController::class, 'index']);
Router::add('/user/save', [\App\Controller\UserController::class, 'save']);

// 404page
Router::add404(function () {
    echo 'No way, this is not a valid path (yet)';
});

Router::run();
exit;

