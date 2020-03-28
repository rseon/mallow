<?php

if (!function_exists('get_meta')) {

    /**
     * Get meta key
     *
     * @param string $key
     * @param null $default
     * @return |null
     */
    function get_meta(string $key, $default = null)
    {
        $registry = Rseon\Mallow\Registry::getInstance();
        if(!$registry->has('meta')) {
            $registry->set('meta', []);
        }
        return $registry->get('meta')[$key] ?? $default;
    }
}

if (!function_exists('set_meta')) {

    /**
     * Set meta key
     *
     * @param string $key
     * @param $value
     */
    function set_meta(string $key, $value)
    {
        $registry = Rseon\Mallow\Registry::getInstance();

        if(!$registry->has('meta')) {
            $registry->set('meta', []);
        }
        $metas = $registry->get('meta');
        $metas[$key] = $value;
        $registry->set('meta', $metas);
    }
}
