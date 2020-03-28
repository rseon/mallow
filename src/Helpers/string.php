<?php


if (!function_exists('normalize_string')) {

    /**
     * Transform a string to unified name.
     *
     * @example : myAction => 'my-action'
     * @example : {controllers_namespace}\Folder\MyNameController => 'folder_my-name'
     * @example : {models_namespace}\Folder\MyModel => 'folder_my-model'
     *
     * @param string $string
     * @return false|string|string[]
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    function normalize_string(string $string)
    {
        $string = str_replace(config('controllers'), '', $string);
        $string = str_replace(config('models'), '', $string);
        $string = ltrim($string, '\\');
        $string = str_replace('\\', '_', $string);
        if(strpos($string, 'Controller') !== false) {
            $string = substr($string, 0, -1*strlen('Controller'));
        }

        $split = str_split($string);
        $chars = [];
        $chars_i = 0;
        foreach($split as $i => $c) {
            preg_match('/([A-Z])/', $c, $matches);
            if(!empty($matches)) {
                if(sizeof($chars) > 1 && $chars[$chars_i-1] !== '_') {
                    $chars[$chars_i] = '-';
                    ++$chars_i;
                    $chars[$chars_i] = $c;
                }
                else {
                    $chars[$chars_i] = $c;
                }
            }
            else {
                $chars[$chars_i] = $c;
            }
            ++$chars_i;
        }

        $string = implode('', $chars);
        $string = strtolower($string);

        return $string;
    }
}

if (!function_exists('sanitize')) {

    /**
     * Sanitize a variable.
     *
     * @param string $data
     * @return false|string
     */
    function sanitize(string $data)
    {
        return strip_tags($data);
    }
}

if (!function_exists('sanitize_array')) {

    /**
     * Sanitize an array
     *
     * @param array $array
     * @return array
     */
    function sanitize_array(array $array)
    {
        foreach($array as $k => $v) {
            if(is_array($v)) {
                sanitize_array($v);
            }
            else {
                $array[$k] = sanitize($v);
            }
        }
        return $array;
    }
}
