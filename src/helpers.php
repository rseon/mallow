<?php

/*
 * Define some helpers functions
 */


if (!function_exists('config')) {

    /**
     * Get configuration
     *
     * @param string|null $id
     * @return mixed
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    function config(string $id = null)
    {
        $config = registry('Config');
        if($id) {
            if(!array_key_exists($id, $config)) {
                throw new Rseon\Mallow\Exceptions\AppException("Config key $id not set");
            }
            return $config[$id];
        }
        return $config;
    }
}

if (!function_exists('get_path')) {

    /**
     * Get path to a file.
     *
     * @param string|null $path
     * @return string
     */
    function get_path(string $path = null)
    {
        if(!$path) {
            return ROOT;
        }
        return ROOT.'/'.ltrim($path, '/');
    }
}

if (!function_exists('registry')) {
    /**
     * Registry.
     *
     * @param string|null $key
     * @param mixed|null $value
     * @return mixed
     */
    function registry(string $key = null, $value = null)
    {
        $registry = Rseon\Mallow\Registry::getInstance();
        if(!$key) {
            return $registry;
        }

        if(!$value) {
            return $registry->get($key);
        }

        return $registry->set($key, $value);
    }
}


