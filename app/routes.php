<?php

/*
 * Define your routes here.
 * Only the index route is required (but you can change its callback).
 *
 * Fell free to add yours !
 */

$Router = new Rseon\Mallow\Router; // Do not remove this line !

// Home page
$Router->get('index', '/', 'IndexController@index');

// Authentication
$Router->map(['GET', 'POST'], 'login', __('login', [], 'routes'), 'AuthController@login');
$Router->map(['GET', 'POST'], 'register', __('register', [], 'routes'), 'AuthController@register');
$Router->get('logout', __('logout', [], 'routes'), 'AuthController@logout');

// Account part (must be logged in)
$Router->get('account', __('account', [], 'routes'), 'AccountController@index');

// Test closure
$Router->get('closure', '/closure-([0-9]+).html', function(int $id, array $request = []) {
    $controller = new Rseon\Mallow\Controllers\ClosureController();
    $controller->view('index', [
        'name' => 'from Closure :)',
        'id' => $id,
        'request' => $request,
    ]);
    $controller->run();
}, ['id']);


return $Router; // Do not remove this line !
