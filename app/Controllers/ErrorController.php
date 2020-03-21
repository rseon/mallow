<?php

namespace App\Controllers;

use Rseon\Mallow\Controller;
use Rseon\Mallow\Exceptions\ControllerException;

class ErrorController extends Controller
{
    public function routeNotFound($url, $method)
    {
        header("HTTP/1.0 404 Not Found");

        container('Debugbar')['exceptions']->addException(new ControllerException("Route not found for URL '$url' with method $method"));
        $this->view('errors.404');
        return $this;
    }
}