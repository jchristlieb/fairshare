<?php

use App\Library\Router;

// require the composer autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// require bootstrap.php which contains some pre-defined constants
require_once dirname(__DIR__) . '/app/bootstrap.php';

Router::init();

// route to dashboard 
Router::add('/', [\App\Controller\IndexController::class, 'index']);

// route to expense
Router::add('/expense', [\App\Controller\ExpenseController::class, 'index']);

// 404page
Router::add404(function () {
    echo 'No way, this is not a valid path (yet)';
});

Router::run();
exit;

