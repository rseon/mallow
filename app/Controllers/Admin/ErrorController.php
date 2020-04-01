<?php

namespace App\Controllers\Admin;

use App\Models\Admin\User;
use Rseon\Mallow\Exceptions\ControllerException;

class ErrorController extends AbstractAdminController
{

    /**
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function routeNotFound()
    {
        $controller = $this->request('controller');
        $action = $this->request('action');

        header("HTTP/1.0 404 Not Found");

        registry('Debugbar')['exceptions']->addException(new ControllerException("Route not found for controller '$controller' with action $action"));
        $this->setHeaderText('404 Error Page');
        $this->view('errors.404');
    }
}