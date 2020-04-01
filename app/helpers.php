<?php

if(!function_exists('admin_url')) {

    /**
     * @param string $path
     * @param array $params
     * @return string
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    function admin_url(string $path, array $params = [])
    {
        $url = url(config('path_admin').$path);

        if($params) {
            $url .= '?'.http_build_query($params);
        }

        return $url;
    }
}
