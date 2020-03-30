<?php

namespace App\Controllers\Admin;

class IndexController extends AbstractAdminController
{
    /**
     * Index page
     *
     * @return $this
     */
    public function index()
    {
        $this->view('index');
    }
}