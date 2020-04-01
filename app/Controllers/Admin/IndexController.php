<?php

namespace App\Controllers\Admin;

class IndexController extends AbstractAdminController
{

    public function index()
    {
        $this->breadcrumbs();
        $this->setHeaderText('Tableau de bord', 'It all starts here');
        $this->setTitle('Tableau de bord');
        $this->view('index');
    }

    public function testRoute($id)
    {
        dump($id);
        $this->view('index');
    }
}