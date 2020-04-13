<?php

namespace Rseon\Mallow\Controllers;

use Rseon\Mallow\Controller;
use Rseon\Mallow\Exceptions\ControllerException;

/**
 * Class ErrorController
 * @package Rseon\Mallow\Controllers
 * @since 1.0
 */
class ErrorController extends Controller
{

    /**
     * @throws ControllerException
     * @since 1.0
     */
    public function routeNotFound()
    {
        $url = $this->request('url');
        $method = $this->request('method');

        header("HTTP/1.0 404 Not Found");
        throw new ControllerException("Route not found for URL '$url' with method $method.");
    }

    /**
     * @throws ControllerException
     * @since 1.5
     */
    public function controllerNotFound()
    {
        $controller = $this->request('controller');

        header("HTTP/1.0 404 Not Found");
        throw new ControllerException("Controller '$controller' not found.");
    }

    /**
     * @throws ControllerException
     * @since 1.5
     */
    public function actionNotFound()
    {
        $controller = $this->request('controller');
        $action = $this->request('action');

        header("HTTP/1.0 404 Not Found");
        throw new ControllerException("Action '$action' not found in controller '$controller'.");
    }

    /**
     * @throws ControllerException
     * @since 1.5
     */
    public function methodNotAllowed()
    {
        $url = $this->request('url');
        $method = $this->request('method');
        $allowed = implode(', ', $this->request('allowed'));

        header("HTTP/1.0 405 Method Not Allowed");
        throw new ControllerException("URL {$url} must be called with methods {$allowed}.");
    }
}