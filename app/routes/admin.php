<?php

/*
 * This is the admin part.
 * There is no route rewriting because are automatic like /$module_path/$controller/$action
 */

use Rseon\Mallow\Router;

/*
 * Get URI path
 */
$path = config('path_admin');

/*
 * Get module namespace
 */
$namespace = config('controllers').'\\Admin';

// Test other route than default
Router::get('admin.test', $path.'/test/(.*)', 'Admin\\IndexController@testRoute', ['id']);

/**
 * Set the default router as /{$path}/{$controller}/{$action}
 */
Router::setDefaultRouter($path, $namespace);
