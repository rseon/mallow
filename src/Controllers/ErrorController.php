<?php

namespace Rseon\Mallow\Controllers;

use Rseon\Mallow\Controller;
use Rseon\Mallow\Exceptions\ControllerException;

class ErrorController extends Controller
{
    public function routeNotFound($url, $method)
    {
        header("HTTP/1.0 404 Not Found");
        throw new ControllerException("Route not found for URL '$url' with method $method");
    }
}