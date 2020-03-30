<?php

/*
 * This is the admin part.
 * The routing is automatic and is : /$module_path/$controller/$action
 */

use Rseon\Mallow\Router;

/*
 * Get URI path
 */
$module_path = \App\Controllers\Admin\AbstractAdminController::PATH_ADMIN;

/*
 * Get module namespace
 */
$module_namespace = config('controllers').'\\Admin';

/*
 * If no controller, redirects to the index controller
 */
Router::map(['GET', 'POST'], 'admin', $module_path, function(array $request = []) {
    redirect(admin_url('/index'));
});

/*
 * If no action, redirects to the index action of the controller
 */
Router::map(['GET', 'POST'], 'admin.controller', "$module_path/(.*)", function(string $controller, array $request = []) {
    redirect(admin_url("/$controller/index"));
}, ['controller']);

/*
 * Runs the controller with its action.
 * And first, check if logged.
 */
Router::map(['GET', 'POST'], 'admin.dispatch', "$module_path/(.*)/(.*)", function(string $controller, string $action, array $request = []) use ($module_namespace) {

    // Redirect if not logged
    if(!in_array($action, ['login', 'logout']) && !(new App\Models\Admin\User)->isAuth()) {
        redirect(admin_url('/auth/login'));
    }

    $controller = normalize_string_reverse($controller, 'controller');
    $action = normalize_string_reverse($action, 'action');

    $controller = $module_namespace.'\\'.$controller.'Controller';

    $controller = new $controller($action);
    if($action) {
        call_user_func_array([$controller, $action], $request);
    }
    else {
        call_user_func_array($controller, $request);
    }
    return $controller->run();
}, ['controller', 'action']);
