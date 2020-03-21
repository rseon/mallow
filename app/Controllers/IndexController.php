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
        return $this;
    }

    /**
     * Test page
     *
     * @param int $id
     * @param array $request
     */
    public function test(int $id, array $request)
    {
        echo __('Localized route');
        dump($id);
    }
}