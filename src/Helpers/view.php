<?php

if(!function_exists('asset')) {

    /**
     * Returns asset path with its version if defined in the public/mix-manifest.json
     *
     * @param string $path
     * @return string
     */
    function asset(string $path)
    {
        $content = registry('manifest_assets');
        if(!$content) {
            $manifest = get_path('/public/mix-manifest.json');
            if(!file_exists($manifest)) {
                return $path;
            }

            $content = json_decode(file_get_contents($manifest), true);
            registry('manifest_assets', $content);
        }

        return $content[$path] ?? $path;
    }
}

if(!function_exists('view')) {

    /**
     * Create new view
     *
     * @param string $path
     * @param array $args
     * @return \Rseon\Mallow\View
     */
    function view(string $path, array $args = [])
    {
        return new Rseon\Mallow\View($path, $args);
    }
}
