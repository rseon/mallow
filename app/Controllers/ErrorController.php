<?php

namespace App\Controllers;

use Rseon\Mallow\Controllers\ErrorController as BaseErrorController;
use Rseon\Mallow\Exceptions\ControllerException;

class ErrorController extends BaseErrorController
{
    public function routeNotFound($url, $method)
    {
        header("HTTP/1.0 404 Not Found");

        registry('Debugbar')['exceptions']->addException(new ControllerException("Route not found for URL '$url' with method $method"));
        $this->view('errors.404');
        return $this;
    }
}