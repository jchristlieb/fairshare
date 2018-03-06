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

        // iterate through all routes defined in index.php
        foreach (self::$routes as $route) {
            
            // Add in front off $route['expression'] e.g /dashboard
            // a '^' -> this reg ex indicates the start of the string
            $route['expression'] = '^' . $route['expression'];
            
            // Add '$' at the end of $route['expression'] 
            // this reg ex indicates the end of the string
            $route['expression'] = $route['expression'] . '$';
       
            // preg_match function searches within self::$path for a match 
            // to the reg ex -> e.g ^/dashboard$ and saves the matches in an 
            // array called $matches
            if (preg_match('#' . $route['expression'] . '#', self::$path, $matches)) {
                
                // first element in $matches contains the entire string. 
                // delete it through array shift thus we are able to make
                // use of (optional) other elements later on as function parameter
                
                // check if function is array if this is the case I assume
                // the route has a controller and method 
                if (is_array($route['function'])) {
                    
                    // run a new instance of assigned class as $controller
                    $controller = new $route['function'][0]();
                  
                    // the function calls the assigned $controller
                    // and handsover the required method name -> $route['function'][1]
                    // and if available further parameters within the $matches array
                    call_user_func_array([$controller, $route['function'][1]], $matches);
                    
                } else {
                    // if route has no controller defined call the associated function
                    // and pass optional parameters through $matches
                    call_user_func_array($route['function'], $matches);
                }
                
                // set the found route to true
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


