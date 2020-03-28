<?php

if (!function_exists('error')) {

    /**
     * Get if input field has an error
     *
     * @param string $field
     * @return |null
     */
    function error(string $field)
    {
        $flash = Rseon\Mallow\FlashMessage::getInstance();
        $errors = $flash->get($flash::ERROR);
        return $errors[$field] ?? null;
    }
}

if (!function_exists('flash')) {

    /**
     * Get flash messenger
     *
     * @return mixed
     */
    function flash()
    {
        return Rseon\Mallow\FlashMessage::getInstance();
    }
}

if (!function_exists('old')) {

    /**
     * Assign or retrieve old input value
     *
     * @example Assign a value : old('foo', 'bar')
     * @example Assign a bunch of values : old(['f' => 'b', 'o' => 'a'])
     * @example Get a value : old('foo') // returns 'bar' or null if not assigned
     *
     * @param string|array $field
     * @param null $value
     * @return mixed|null
     */
    function old($field, $value = null)
    {
        $SESSION_NAME = '__old';
        if(!array_key_exists($SESSION_NAME, $_SESSION)) {
            $_SESSION[$SESSION_NAME] = [];
        }

        if(!$value) {
            // Bunch assignation
            if(is_array($field)) {
                foreach($field as $k => $v) {
                    unset($_SESSION[$SESSION_NAME][$k]);
                    if($v !== '') {
                        old($k, $v);
                    }
                }
                return null;
            }

            // Retrieve
            $current = $_SESSION[$SESSION_NAME][$field] ?? null;
            unset($_SESSION[$SESSION_NAME][$field]);
            return $current;
        }

        // Assignation
        $_SESSION[$SESSION_NAME][$field] = $value;
        return null;
    }
}
