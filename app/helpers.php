<?php

if(!function_exists('admin_url')) {

    /**
     * @param string $path
     * @return string
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    function admin_url(string $path)
    {
        return url(\App\Controllers\Admin\AbstractAdminController::PATH_ADMIN.$path);
    }
}