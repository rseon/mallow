<?php

namespace Rseon\Mallow;

use Rseon\Mallow\Exceptions\RouterException;

class Router
{
    protected $routes = [];
    protected $cached = [];
    protected $cache_current = [];

    protected static $instance;
    public static function getInstance() {
        if (!(static::$instance instanceof static)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Call static methods to manage routes : get, post and map
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws RouterException
     */
    public static function __callStatic($name, $arguments)
    {
        $available = ['get', 'post', 'map'];
        $instance = static::getInstance();

        if(!in_array($name, $available)) {
            throw new RouterException("Method {$name} not found");
        }

        $method = 'addRoute'.ucfirst(strtolower($name));
        return $instance->$method(...$arguments);
    }

    /**
     * Add route with method GET
     *
     * @param string $name
     * @param string $path
     * @param $callback
     * @param array $request
     * @return $this
     */
    public function addRouteGet(string $name, string $path, $callback, array $request = [])
    {
        return $this->addRoute('GET', $name, $path, $callback, $request);
    }

    /**
     * Add route with method POST
     *
     * @param string $name
     * @param string $path
     * @param $callback
     * @param array $request
     * @return $this
     */
    public function addRoutePost(string $name, string $path, $callback, array $request = [])
    {
        return $this->addRoute('POST', $name, $path, $callback, $request);
    }

    /**
     * Add route mapped with methods
     *
     * @param array $methods
     * @param string $name
     * @param string $path
     * @param $callback
     * @param array $request
     * @return $this
     */
    public function addRouteMap(array $methods, string $name, string $path, $callback, array $request = [])
    {
        foreach($methods as $method) {
            $this->addRoute(strtoupper($method), $name, $path, $callback, $request);
        }
    }

    /**
     * Add route
     *
     * @param string $method
     * @param string $name
     * @param string $path
     * @param $callback
     * @param array $request
     * @return $this
     */
    public function addRoute(string $method, string $name, string $path, $callback, array $request = [])
    {
        $method = strtoupper($method);
        if(!array_key_exists($method, $this->routes)) {
            $this->routes[$method] = [];
        }
        $this->routes[$method][$name] = [
            'name' => $name,
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
            'request' => $request,
        ];

        return $this;
    }

    /**
     * Add route as array
     *
     * @param array $data
     * @return $this
     * @throws RouterException
     */
    public function addRouteArray(array $data)
    {
        $keys = ['method', 'name', 'path', 'callback'];
        $missing_keys = [];
        foreach($keys as $k) {
            if(!isset($data[$k])) {
                $missing_keys[] = $k;
            }
        }
        if(!empty($missing_keys)) {
            throw new RouterException("Params missing to add route : ".implode(', ', $missing_keys));
        }

        return $this->add($data['method'], $data['name'], $data['path'], $data['callback'], $data['request'] ?? []);
    }

    /**
     * Remove route
     *
     * @param string $method
     * @param string $name
     * @return $this
     */
    public function removeRoute(string $method, string $name)
    {
        unset($this->routes[$method][$name]);
        return $this;
    }

    /**
     * Get routes
     *
     * @param string|null $method
     * @return array|mixed
     */
    public function getRoutes(string $method = null)
    {
        if(!$method) {
            return $this->routes;
        }
        return $this->routes[strtoupper($method)] ?? [];
    }

    /**
     * Get current route from URL
     *
     * @param string|null $current_url
     * @return array|mixed|null
     * @throws Exceptions\AppException
     */
    public function getCurrentRoute(string $current_url = null)
    {
        if($this->cache_current) {
            return $this->cache_current;
        }

        if(!$current_url) {
            $current_url = $_SERVER['REQUEST_URI'];
        }

        $url = explode('?', $current_url)[0];
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $routes = $this->getRoutes($method);

        $current = null;

        foreach($routes as $name => $route) {
            if($route['path'] === $url) {
                $current = $route;
                break;
            }

            // Regex path
            if(strpos($route['path'], '(') !== false && $route['request']) {
                $url_components = explode('/', $url);
                $path_components = explode('/', $route['path']);
                array_shift($url_components);
                array_shift($path_components);

                if(count($url_components) !== count($path_components)) {
                    continue;
                }

                $current = $route;
                $request = $current['request'];
                $current['request'] = [];

                $current_i = 0;
                foreach($path_components as $idx => $component) {
                    if(strpos($component, '(') !== false) {
                        $pattern = "@^".$component."$@D";
                        $matches = [];
                        preg_match($pattern, $url_components[$idx], $matches);
                        if($matches) {
                            array_shift($matches);

                            foreach($matches as $i => $m) {
                                $current['request'][$request[$current_i]] = $m;
                                ++$current_i;
                            }
                        }
                        else {
                            $current = null;
                            continue 2;
                        }
                    }
                    else {
                        if($component !== $url_components[$idx]) {
                            $current = null;
                            continue 2;
                        }
                    }
                }

                break;
            }
        }

        if(!$current) {
            $namespace = config('controllers');
            $controller = $namespace.'\\ErrorController';
            if(!class_exists($controller)) {
                $namespace = 'Rseon\\Mallow\\Controllers';
            }

            $current = [
                'name' => 'routeNotFound',
                'callback' => 'ErrorController@routeNotFound',
                'namespace' => $namespace,
                'request' => [
                    'url' => $url,
                    'method' => $method,
                ]
            ];
        }

        $current['request']['request'] = array_merge($_GET, $_POST);

        $this->cache_current = $current;
        return $current;
    }

    /**
     * Get a route by its name
     *
     * @param string $name
     * @param string|null $method
     * @return mixed
     * @throws RouterException
     */
    public function getRoute(string $name, string $method = null)
    {
        $routes = $this->getRoutes();
        $method = strtoupper($method);
        foreach($routes as $currentMethod => $routesMethod) {
            if($method && $currentMethod !== $method) {
                continue;
            }
            if(array_key_exists($name, $routesMethod)) {
                return $routesMethod[$name];
            }
        }

        // Route not found
        $_message = "Route $name not found";
        if($method) {
            $_message .= " with method $method";
        }
        throw new RouterException($_message);
    }

    /**
     * Get the url of a route based on its parameters
     *
     * @param array $route
     * @param array $params
     * @return mixed|string|string[]|null
     * @throws RouterException
     */
    public function getUrl(array $route, $params = [])
    {
        $path = $route['path'] ?? null;

        // Check params
        $missing_params = [];
        foreach($route['request'] as $k => $v) {
            if(is_array($v)) {
                continue;
            }
            if(!is_numeric($k)) {
                continue;
            }
            if(!isset($params[$v])) {
                $missing_params[] = $v;
            }
        }
        if($missing_params) {
            throw new RouterException("Params missing for route {$route['name']} : ".implode(', ', $missing_params));
        }

        // Check regex
        if($path && strpos($path, '(') !== false) {
            $components = explode('/', $path);
            array_shift($components);
            foreach($components as $idx => $component) {
                $splitted = str_split($component);
                $patterns = [];
                $current_i = 0;
                foreach($splitted as $i => $s) {
                    if($s === '(') {
                        $patterns[$current_i] = '';
                    }

                    if(isset($patterns[$current_i])) {
                        $patterns[$current_i] .= $s;
                        if($s === ')') {
                            ++$current_i;
                        }
                    }
                }

                $params_keys = array_keys($params);
                $current_i = 0;
                foreach($patterns as $i => $p) {
                    if(strpos($p, '(') !== false) {
                        $path = preg_replace('/'.preg_quote($p, '/').'/', $params[$params_keys[$current_i]], $path, 1);
                        unset($params[$params_keys[$current_i]]);
                        ++$current_i;
                    }
                }
            }
        }

        if(sizeof($params) > 0) {
            $request = [];
            if(isset($params['request'])) {
                $request = $params['request'];
                unset($params['request']);
            }
            $request = array_merge($params, $request);
            $path .= '?'.http_build_query($request);
        }
        $path = rtrim($path, '?');
        return url($path);
    }

    /**
     * Get the route URL by name and parameters
     *
     * @param string $name
     * @param array $params
     * @param string|null $method
     * @return mixed|string|string[]|null
     * @throws RouterException
     */
    public function routeTo(string $name, array $params = [], string $method = null)
    {
        $route_key = sha1($name.json_encode($params).$method);
        if(array_key_exists($route_key, $this->cached)) {
            return $this->cached[$route_key];
        }

        try {
            $route = $this->getRoute($name, $method);
        } catch (RouterException $e) {
            registry('Debugbar')['exceptions']->addException($e);
            return null;
        }

        $url = $this->getUrl($route, $params);

        $this->cached[$route_key] = $url;
        return $url;
    }

    /**
     * Performs a redirection
     *
     * @param string $url
     */
    public function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * Get list of cached routes
     *
     * @return array
     */
    public function getCached()
    {
        return $this->cached;
    }

    /**
     * Execute current route callback
     *
     * @param string $namespace
     * @return mixed
     * @throws Exceptions\AppException
     */
    public function dispatch(string $namespace)
    {
        $current = $this->getCurrentRoute();
        registry('Debugbar')->getCollector('route')->set($current);

        // Is it closure ?
        if(gettype($current['callback']) === 'object') {
            return call_user_func_array($current['callback'], $current['request']);
        }

        if(isset($current['namespace'])) {
            $namespace = $current['namespace'];
        }

        $action = null;
        $controller = "{$namespace}\\{$current['callback']}";
        if(strpos($controller, '@') !== false) {
            list($controller, $action) = explode('@', $controller);
        }

        $controller = new $controller($action);
        if($action) {
            call_user_func_array([$controller, $action], $current['request']);
        }
        else {
            call_user_func_array($controller, $current['request']);
        }
        return $controller->run();
    }

}