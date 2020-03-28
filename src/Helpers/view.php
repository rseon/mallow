<?php

if(!function_exists('view')) {

    /**
     * Create new view
     *
     * @param string $path
     * @param array $args
     * @return \Rseon\Mallow\View
     */
    function view(string $path, array $args = [])
    {
        return new Rseon\Mallow\View($path, $args);
    }
}
