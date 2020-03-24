<?php

/*
 *
 */

$Router = new Rseon\Mallow\Router;

$Router->get('index', '/', 'IndexController@index');
$Router->map(['GET', 'POST'], 'index.test', __('index.test', [], 'routes'), 'IndexController@test', ['id']);
$Router->post('testform', '/testform', 'IndexController@testform');

// Test closure
$Router->get('closure', '/closure', function() {
    $controller = new Rseon\Mallow\Controllers\ClosureController();
    $controller->view('index', ['name' => 'Mallow']);
    $controller->run();
});

return $Router;
