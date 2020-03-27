<?php

namespace App\Controllers;

use Rseon\Mallow\Controller;

class IndexController extends Controller
{
    /**
     * Index page
     *
     * @param array $request
     * @return $this
     */
    public function index(array $request)
    {
        $this->setTitle("Mallow - Another PHP Framework");

        $name = 'Mallow';
        $this->view('index', compact('name'));
    }
}