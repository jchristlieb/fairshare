<?php

namespace App\Controller;

use Twig_Loader_Filesystem;

abstract class Controller
{
    /**
     *
     * @var \Twig_Environment
     */
    protected $twig;
    
    /**
     * Initiate a new Twig Session 
     */
    public function __construct() 
    {
        $loader = new Twig_Loader_Filesystem(VIEW_DIR);
        $this->twig = new \Twig_Environment($loader);
    }
    
    protected function redirect($url)
    {
        header('Location: ' . $url, true, 302);
        exit();
    }
    
}