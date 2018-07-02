<?php

namespace App\Library;

class SessionManager
{
    public function __construct()
    {
        session_start();
    }

    public static function set($key, $value)
    {
        //set something in session
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        //return the value from the session
        return $_SESSION[$key];
    }
}