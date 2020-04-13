<?php

namespace App\Controllers;

use Rseon\Mallow\Controllers\ErrorController as BaseErrorController;
use Rseon\Mallow\Exceptions\ControllerException;

class ErrorController extends BaseErrorController
{

    /**
     * Route not found (404)
     */
    public function routeNotFound()
    {
        $url = $this->request('url');
        $method = $this->request('method');

        header("HTTP/1.0 404 Not Found");

        if(debug()->isEnabled()) {
            debug()->getDebugbar()['exceptions']->addException(new ControllerException("Route not found for URL '$url' with method $method"));
        }
        $this->view('errors.404');
    }

    /**
     * Controller not found (404)
     */
    public function controllerNotFound()
    {
        $controller = $this->request('controller');

        header("HTTP/1.0 404 Not Found");

        if(debug()->isEnabled()) {
            debug()->getDebugbar()['exceptions']->addException(new ControllerException("Controller '$controller' not found"));
        }
        $this->view('errors.404');
    }

    /**
     * Action not found (404)
     */
    public function actionNotFound()
    {
        $controller = $this->request('controller');
        $action = $this->request('action');

        header("HTTP/1.0 404 Not Found");

        if(debug()->isEnabled()) {
            debug()->getDebugbar()['exceptions']->addException(new ControllerException("Action '$action' not found in controller '$controller'"));
        }
        $this->view('errors.404');
    }
}