<?php

if (!function_exists('check_password')) {

    /**
     * Check a password
     *
     * @param $password
     * @param $hash
     * @return bool
     */
    function check_password($password, $hash)
    {
        return password_verify(make_hash($password), $hash);
    }
}

if (!function_exists('keygen_alphanum')) {

    /**
     * Generate an alphanumeric key
     *
     * @param int $length
     * @return string
     *
     * @link https://github.com/gladchinda/keygen-php/blob/2.0.0-alpha/src/Keygen/Generators/AlphaNumericGenerator.php
     */
    function keygen_alphanum(int $length = 32)
    {
        $chunkFactor = 5;

        // 1. Initiates a long alphanumeric character sequence
        $numeric = range(0, 9);
        $bigAlpha = range('A', 'Z');
        $smallAlpha = range('a', 'z');
        $chars = array_merge($numeric, $smallAlpha, $numeric, $bigAlpha, $numeric);
        shuffle($chars);
        $chars = str_shuffle(str_rot13(join('', $chars)));

        // 2. Generates a set character chunks based on length
        $chunkArray = array();
        $size = strlen($chars);
        $split = intval(ceil($length / $chunkFactor));
        $splitSize = ceil($size / $split);
        $chunkSize = $chunkFactor + $splitSize + mt_rand(1, $chunkFactor);
        $chars = str_shuffle(str_repeat($chars, 2));
        $size = strlen($chars);

        while ($split != 0) {
            $strip = substr($chars, mt_rand(0, $size - $chunkSize), $chunkSize);
            array_push($chunkArray, strrev($strip));
            $split--;
        }

        $chunks = $chunkArray;

        // 3. Generates an alphanumeric character sequence
        $chars = '';

        foreach ($chunks as $set) {
            $modulus = ($length - strlen($chars)) % $chunkFactor;
            $adjusted = ($modulus > 0) ? $modulus : $chunkFactor;
            $chars .= substr($set, mt_rand(0, strlen($set) - $adjusted), $adjusted);
        }

        $key = $chars;

        // 4. Generates a random key
        return str_rot13(str_shuffle($key));
    }
}

if (!function_exists('make_hash')) {

    /**
     * Hash a value
     *
     * @param $value
     * @return false|string|null
     */
    function make_hash($value)
    {
        return hash('sha256', $value.getenv('APP_KEY'));
    }
}

if (!function_exists('make_password')) {

    /**
     * Create a password
     *
     * @param $value
     * @return false|string|null
     */
    function make_password($password)
    {
        return password_hash(make_hash($password), PASSWORD_DEFAULT);
    }
}

if (!function_exists('token')) {

    /**
     * Generates a random key.
     *
     * @param int $length
     * @return false|string
     *
     * @link https://github.com/gladchinda/keygen-php/blob/2.0.0-alpha/src/Keygen/Generators/TokenGenerator.php
     */
    function token(int $length = 32)
    {
        $token = '';
        $tokenlength = round($length * 4 / 3);

        for ($i = 0; $i < $tokenlength; ++$i) {
            $token .= chr(rand(32,1024));
        }

        $token = base64_encode(str_shuffle($token));

        return substr($token, 0, $length);
    }
}



