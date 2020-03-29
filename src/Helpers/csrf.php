<?php

if(!function_exists('check_csrf')) {

    /**
     * Check if CSRF is set and is correct
     *
     * @param null $data
     * @return mixed
     */
    function check_csrf($data = null)
    {
        return csrf()->check($data);
    }
}

if(!function_exists('check_csrf_array')) {

    /**
     * Check CSRF in an array
     *
     * @param null $data
     * @return mixed
     */
    function check_csrf_array(array $data = [])
    {
        return check_csrf($data[get_csrf_name()] ?? null);
    }
}

if(!function_exists('csrf')) {

    /**
     * Get CSRF instance
     *
     * @return mixed
     */
    function csrf()
    {
        return Rseon\Mallow\Csrf::getInstance();
    }
}

if(!function_exists('csrf_input')) {

    /**
     * Get input to send CSRF in form
     *
     * @param string $type
     * @return mixed
     */
    function csrf_input(string $type = 'hidden')
    {
        return '<input type="'.$type.'" name="'.csrf()->getTokenName().'" value="'.csrf()->getToken().'" />';
    }
}

if(!function_exists('get_csrf')) {

    /**
     * Returns CSRF token
     *
     * @return mixed
     */
    function get_csrf()
    {
        return csrf()->getToken();
    }
}

if(!function_exists('get_csrf_name')) {

    /**
     * Returns CSRF token
     *
     * @return mixed
     */
    function get_csrf_name()
    {
        return csrf()->getTokenName();
    }
}
