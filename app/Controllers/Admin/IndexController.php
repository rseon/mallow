<?php

namespace App\Controllers\Admin;

class IndexController extends AbstractAdminController
{
    /**
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function index()
    {
        $this->breadcrumbs();
        $this->setHeader('Tableau de bord', 'Where all starts...');
        $this->view('index');
    }
}