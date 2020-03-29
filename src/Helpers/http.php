<?php

if (!function_exists('get_xhr_token')) {

    /**
     * Returns token sent during XHR
     *
     * @return bool
     */
    function get_xhr_token()
    {
        return $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
    }
}

if (!function_exists('is_post')) {

    /**
     * Get if request method is POST
     *
     * @return bool
     */
    function is_post()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}

if (!function_exists('is_xhr')) {

    /**
     * Get if request is an XHR
     *
     * @return bool
     */
    function is_xhr()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
}

if (!function_exists('json')) {

    /**
     * Send a JSON response
     *
     * @param null $data
     */
    function json($data = null)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
