<?php

if (!function_exists('__')) {

    /**
     * Translate a string
     *
     * @param string $string
     * @param array $params
     * @param string|null $section
     * @param string|null $locale
     * @return mixed
     */
    function __(string $string, array $params = [], string $section = null, string $locale = null)
    {
        return Rseon\Mallow\Locale::getInstance()->translate($string, $params, $section, $locale);
    }
}

if (!function_exists('get_current_url_locale')) {

    /**
     * Get current route with locale
     *
     * @param string $locale
     * @return mixed
     */
    function get_current_url_locale(string $locale)
    {
        if($locale === get_locale()) {
            return get_current_url();
        }

        $current = get_current_route();

        $prev_locale = get_locale();
        set_locale($locale);

        if(Rseon\Mallow\Locale::getInstance()->exists($locale, 'routes', $current['name'])) {
            $current['path'] = __($current['name'], [], 'routes');
        }

        $url = router()->getUrl($current, $current['request']);
        set_locale($prev_locale);

        return $url;
    }
}

if (!function_exists('get_locale')) {

    /**
     * Get current locale
     *
     * @return mixed
     */
    function get_locale()
    {
        return Rseon\Mallow\Locale::getInstance()->getLocale();
    }
}

if (!function_exists('set_locale')) {

    /**
     * Set current locale
     *
     * @param string|null $locale
     */
    function set_locale(string $locale = null)
    {
        if(!$locale) {
            foreach(config('locales') as $code => $localeData) {
                if (!isset($localeData['subdomain'])) {
                    continue;
                }
                $subdomain = $localeData['subdomain'];
                if ($subdomain !== '') {
                    $components = explode('.', $_SERVER['HTTP_HOST']);
                    if (isset($components[0]) && $components[0] === $code) {
                        $locale = $code;
                        break;
                    }
                }
            }
        }

        if(!$locale) {
            $locale = config('locale');
        }

        Rseon\Mallow\Locale::getInstance()->setLocale($locale);
    }
}

if (!function_exists('route_locale')) {

    /**
     * Localize a route
     *
     * @param string $locale
     * @param string $name
     * @param array $params
     * @param string|null $method
     * @return mixed|string|string[]
     */
    function route_locale(string $locale, string $name, array $params = [], string $method = null)
    {
        if($locale === get_locale()) {
            return route($name, $params, $method);
        }

        $prev_locale = get_locale();
        set_locale($locale);

        $router = router();
        $route = $router->getRoute($name, $method);

        if(Rseon\Mallow\Locale::getInstance()->exists($locale, 'routes', $route['name'])) {
            $route['path'] = __($route['name'], [], 'routes');
        }

        $url = $router->getUrl($route, $params);

        set_locale($prev_locale);

        return $url;
    }
}