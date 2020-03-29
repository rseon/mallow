<?php

if(!function_exists('asset')) {


    function asset(string $path)
    {
        $manifest = get_path('/public/mix-manifest.json');
        if(!file_exists($manifest)) {
            return $path;
        }
        $content = json_decode(file_get_contents($manifest), true);
        return $content[$path] ?? $path;
    }
}

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
