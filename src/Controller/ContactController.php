<?php

namespace App\Controller;

class ContactController extends Controller
{
    /**
     * Retreive the home page
     * 
     */
    public function index()
    {
        return $this->render('contact/index.html.twig');
    }
}
