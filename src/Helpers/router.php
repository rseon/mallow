<?php

if (!function_exists('get_current_route')) {

    /**
     * Get current route
     *
     * @param string|null $key
     * @return mixed
     */
    function get_current_route(string $key = null)
    {
        $current = router()->getCurrentRoute();
        if(!$key) {
            return $current;
        }

        return $current[$key];
    }
}

if (!function_exists('get_current_url')) {

    /**
     * Get current route
     *
     * @return mixed
     */
    function get_current_url()
    {
        $route = get_current_route();
        return router()->getUrl($route, $route['request']);
    }
}

if (!function_exists('no_query_string')) {

    /**
     * Remove query string from url, except params in $except
     *
     * @param string $url
     * @param array $except
     * @return string|string[]
     */
    function no_query_string(string $url, array $except = [])
    {
        $components = parse_url($url);

        if(isset($components['query'])) {
            $url = str_replace('?'.$components['query'], '', $url);

            if($except) {
                parse_str($components['query'], $query);
                $query = array_filter($query, function($key) use ($except) {
                    return in_array($key, $except);
                }, ARRAY_FILTER_USE_KEY);

                if($query) {
                    $url .= '?'.http_build_query($query);
                }
            }
        }

        return $url;
    }
}

if (!function_exists('redirect')) {

    /**
     * Redirect to an url
     *
     * @param string $url
     * @return mixed
     */
    function redirect(string $url)
    {
        return router()->redirect($url);
    }
}

if (!function_exists('router')) {

    /**
     * Get router
     *
     * @return mixed
     */
    function router()
    {
        return registry('Router');
    }
}

if (!function_exists('route')) {

    /**
     * Returns a route.
     *
     * @param string $name
     * @param array $params
     * @param string $method
     * @return mixed|string|string[]
     */
    function route(string $name, array $params = [], string $method = null)
    {
        return router()->routeTo($name, $params, $method);
    }
}

if (!function_exists('url')) {

    /**
     * Returns an url
     *
     * @param string $path
     * @param array $params
     * @return string
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    function url(string $path, array $params = [])
    {
        $protocol = getenv('APP_HTTPS') ? 'https://' : 'http://';
        $domain = getenv('APP_DOMAIN');

        $subdomain = config('locales')[get_locale()]['subdomain'] ?? '';
        if($subdomain) {
            $domain = $subdomain . '.' . $domain;
        }

        if($params) {
            $path .= '?'.http_build_query($params);
        }

        return $protocol.$domain.$path;
    }
}
