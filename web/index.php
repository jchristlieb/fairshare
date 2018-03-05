<?php

use App\Library\Router;

// require the composer autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// require bootstrap.php which contains some pre-defined constants
require_once dirname(__DIR__) . '/app/bootstrap.php';

Router::init();

Router::add('/', [\app\Contro])

