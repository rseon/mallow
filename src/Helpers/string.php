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

if (!function_exists('normalize_string_reverse')) {

    /**
     * Reverse a transformed unified string.
     *
     * @example : 'my-action' => 'myAction'
     * @example : 'folder_my-name' => 'Folder\MyName'
     *
     * @param string $string
     * @return false|string|string[]
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    function normalize_string_reverse(string $string, string $type = 'controller')
    {
        $unNormalize = function($string) {
            $actionParts = explode('-', $string);
            return implode('', array_map(function($key, $value) {
                $value = strtolower($value);
                if($key > 0) {
                    $value = ucfirst($value);
                }
                return $value;
            }, array_keys($actionParts), array_values($actionParts)));
        };

        if($type === 'action') {
            return $unNormalize($string);
        }

        if(strpos($string, '_') !== false) {
            $folderParts = explode('_', $string);
            foreach($folderParts as $k => $p) {
                $normalized = $unNormalize($p);
                $normalized = ucfirst($normalized);
                $folderParts[$k] = $normalized;
            }
            $string = implode('\\', $folderParts);
        }
        else {
            $string = ucfirst($unNormalize($string));
        }

        return $string;
    }
}

if (!function_exists('sanitize')) {

    /**
     * Sanitize a variable.
     *
     * @param string|array $data
     * @return false|string
     */
    function sanitize($data)
    {
        if(is_array($data)) {
            foreach($data as $k => $v) {
                if(is_array($v)) {
                    sanitize($v);
                }
                else {
                    $data[$k] = sanitize($v);
                }
            }

            return $data;
        }

        //$data = htmlentities($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return strip_tags($data);
    }
}
