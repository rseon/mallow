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

if (!function_exists('make_hash')) {

    /**
     * Hash a value
     *
     * @param $value
     * @return false|string|null
     */
    function make_hash($value)
    {
        return password_hash($value.getenv('APP_KEY'), PASSWORD_DEFAULT);
    }
}

if (!function_exists('check_hash')) {

    /**
     * Check a hash is correct
     *
     * @param $value
     * @param $hash
     * @return bool
     */
    function check_hash($value, $hash)
    {
        return password_verify($value.getenv('APP_KEY'), $hash);
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


