<?php

namespace App\Controller;

class HomeController extends Controller
{
    /**
     * Retreive the home page
     * 
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }
}
