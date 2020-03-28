<?php

namespace Rseon\Mallow;

use Rseon\Mallow\Exceptions\AppException;

class Csrf
{
    const NAME = '__token';
    protected $token;

    protected static $instance;
    public static function getInstance() {
        if (!(static::$instance instanceof static)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Get current token.
     * Generate it if inexistant.
     *
     * @return string
     * @throws AppException
     */
    public function getToken()
    {
        if(!$this->token) {
            $this->token = $this->generate();
            $_SESSION[static::NAME] = $this->token;
        }
        return $this->token;
    }

    /**
     * Get token name
     *
     * @return string
     */
    public function getTokenName()
    {
        return static::NAME;
    }

    /**
     * Check if token is correct
     *
     * @param $data
     * @return bool
     */
    public function check($data)
    {
        if(!isset($_SESSION[static::NAME]) || !$_SESSION[static::NAME]) {
            return false;
        }
        return $data === $_SESSION[static::NAME];
    }

    /**
     * Generate a token
     *
     * @param int $length
     * @return string
     * @throws AppException
     */
    protected function generate($length = 32)
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes($length);
        }

        elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length);
        }

        elseif (@file_exists('/dev/urandom') && $length < 100) {
            $bytes = file_get_contents('/dev/urandom', false, null, 0, $length);
        }

        else {
            throw new AppException('Unable to generate binary data.');
        }

        return hash('sha256', $bytes.getenv('APP_KEY'));
    }
}