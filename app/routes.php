<?php

/*
 *
 */

$Router = new Rseon\Mallow\Router;

$Router->get('index', '/', 'IndexController@index');

// Switch locale
$Router->get('locale', '/locale/(.*)', function(string $locale, array $request = []) {
    set_locale($locale);
    redirect($_SESSION['redirect'] ?? route('index'));
}, ['locale']);

$Router->map(['GET', 'POST'], 'index.test', __('index.test', [], 'routes'), 'IndexController@test', ['id']);

$Router->post('testform', '/testform', 'IndexController@testform');

return $Router;
