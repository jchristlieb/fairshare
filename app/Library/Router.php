<?php

namespace App\Library;

class Router
{
    /**
     * @var $routes array contains all registered routes
     * @var $callback404 callable contains the 404 callback
     * @var $path string contains the requested path
     */
    public static $routes = [];
    public static $callback404;
    public static $path;

    /**
     * parse uri from requested url
     * e.g. dashboard.php from http://www.fairshare.de/dashboard.php
     */
    public static function init()
    {
        $parsedUrl = parse_url($_SERVER['REQUEST_URI']);
        // if a path is set, save it into $path
        if ( isset($parsedUrl['path']) ) {
            self::$path = $parsedUrl['path'];
        // else save '/' into $path
        } else {
            self::$path = '/';
        }
    }
    

    /**
     * Adds a new route to the router
     *
     * @param $expression string defines the matched controller
     * @param $function callable defines the matched method within the controller
     */
    public static function add($expression, $function)
    {
        array_push(self::$routes, [
            'expression' => $expression,
            'function' => $function
        ]);
    }

    /**
     * Adds the 404 callback to the router
     * @param $function callable
     */
    public static function add404($function)
    {
        self::$callback404 = $function;
    }

    public static function run()
    {
        // set default
        $routeFound = false;

        // search with regex whether called route matches with any defined route
        foreach (self::$routes as $route) {
            // Add 'find string start' automatically
            $route['expression'] = '^' . $route['expression'];
            // Add 'find string end' automatically
            $route['expression'] = $route['expression'] . '$';
            // check match
            if (preg_match('#' . $route['expression'] . '#', self::$path, $matches)) {
                // always remove first element. This contains the whole string
                array_shift($matches);
                // check if function is array which is the way to use class method
                if (is_array($route['function'])) {
                    $controller = new $route['function'][0]();
                    call_user_func_array([$controller, $route['function'][1]], $matches);
                } else {
                    call_user_func_array($route['function'], $matches);
                }
                $routeFound = true;
                break;

            }
        }

        // if no route is found and a 404 callback is registered, call it!
        if (!$routeFound && self::$callback404) {
            call_user_func_array(self::$callback404, [self::$path]);
        }
    }

}


