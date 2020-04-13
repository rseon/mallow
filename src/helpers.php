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

if (!function_exists('debug')) {

    /**
     * Get the debuger
     *
     * @return mixed
     */
    function debug()
    {
        return \Rseon\Mallow\Debug::getInstance();
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

if (!function_exists('load_config')) {

    /**
     * Load configuration files
     *
     * @return array
     */
    function load_config()
    {
        $configSrc = require_once get_path('/src/default_config.php');
        $userConfig = get_path('/app/config.php');
        $configApp = [];
        if(file_exists($userConfig)) {
            $configApp = require_once $userConfig;
        }
        return merge_arrays($configSrc, $configApp);
    }
}

if (!function_exists('merge_arrays')) {

    /**
     * Merge arrays
     *
     * @param mixed ...$arrays
     * @return array
     * @since 1.5
     */
    function merge_arrays(...$arrays)
    {
        $merged = [];
        foreach($arrays as $array) {
            foreach($array as $k => $v) {
                if(!array_key_exists($k, $merged)) {
                    $merged[$k] = $v;
                }
                if(is_array($v)) {
                    $merged[$k] = merge_arrays($merged[$k], $v);
                }
                else {
                    $merged[$k] = $v;
                }
            }
        }
        return $merged;
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


