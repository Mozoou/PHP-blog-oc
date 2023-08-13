<?php

namespace App\Controller\Admin;

use App\Controller\Controller;

abstract class AbstractCrudController extends Controller
{
    abstract public function index();
}