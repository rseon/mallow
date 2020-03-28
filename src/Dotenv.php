<?php

namespace Rseon\Mallow;

use Rseon\Mallow\Exceptions\AppException;

class Dotenv
{
    const FILENAME = '.env';

    /**
     * @param $path
     * @param string|null $filename
     * @throws AppException
     */
    public static function load($path, string $filename = null)
    {
        if(!$filename) {
            $filename = static::FILENAME;
        }

        $file = $path.'/'.$filename;
        if(!file_exists($file)) {
            throw new AppException("Env file [$filename] not found in $path");
        }

        $conf = parse_ini_file($file, true);
        if($conf === false) {
            throw new AppException("Failed parsing [$filename] file.");
        }

        foreach($conf as $k => $v) {
            if($v === '1' || $v === 'true') {
                $v = true;
            }
            if($v === '0' || $v === 'false') {
                $v = false;
            }

            putenv("$k=$v");
        }

    }
}