<?php

namespace App\Controllers;

use Rseon\Mallow\Controller;

class IndexController extends Controller
{
    /**
     * Index page
     */
    public function index()
    {
        $this->setTitle("Mallow - Another PHP Framework");

        $name = 'Mallow';
        $this->view('index', compact('name'));
    }
}