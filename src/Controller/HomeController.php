<?php

namespace App\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        echo $this->render('index.html.twig');
    }
}