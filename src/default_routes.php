<?php

/*
 * This is the base routes.
 *
 * This file shouldn't be modified but overrided with your own routes file.
 */

use Rseon\Mallow\Router;

// Home page
Router::get('index', '/', 'IndexController@index');

// Access to an uploaded file
Router::get('upload.get_file', '/upload/{id}', function($id) {
    $file = get_path(config('upload')['path']).'/'.$id;
    if(!file_exists($file)) {
        header("HTTP/1.0 404 Not Found");
        exit;
    }

    // @todo Log download counter

    header('Content-Type:' . mime_content_type($file));
    echo file_get_contents($file);
});
