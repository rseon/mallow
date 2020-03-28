<?php

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
