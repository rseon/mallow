<?php

/*
 *
 */

$Router = new Rseon\Mallow\Router;

$Router->get('index', '/', 'IndexController@index');
$Router->get('index.test', __('index.test', [], 'routes'), 'IndexController@test', ['id']);

return $Router;
