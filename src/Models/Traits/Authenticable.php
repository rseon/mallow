<?php

namespace Rseon\Mallow\Models\Traits;

trait Authenticable
{
    protected $reason_not_logged;

    /**
     * Initialize the trait
     */
    public function initAuthenticableTrait()
    {
        if($this->isAuth()) {
            $this->setAuth($this->getAuth());
        }
    }

    /**
     * Try to authenticate
     *
     * @param string $identifier
     * @param string $password
     * @param bool $remember
     * @return Authenticable
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function auth(string $identifier, string $password, $remember = false)
    {
        $this->attemptLogin($identifier, $password);

        if($this->isAuth() && $remember) {
            $this->setRememberMe();
        }

        return $this;
    }

    /**
     * Check if is authenticated
     *
     * @return bool
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function isAuth()
    {
        return !empty($this->getAuth());
    }

    /**
     * Set authenticate data
     *
     * @param bool $data
     * @return $this
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function setAuth($data = false)
    {
        $_SESSION[$this->getAuthSessionName()] = $data;
        return $this;
    }

    /**
     * Get authenticate data
     *
     * @return array|mixed
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function getAuth()
    {
        return array_key_exists($this->getAuthSessionName(), $_SESSION)
            ? $_SESSION[$this->getAuthSessionName()]
            : [];
    }

    /**
     * Get reason if non-auth
     *
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason_not_logged;
    }

    /**
     * @return string
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function getAuthSessionName()
    {
        return 'auth_'.(defined('static::AUTH_SESSION_NAME')
                ? static::AUTH_SESSION_NAME
                : normalize_string(static::class));
    }

    /**
     * @return string
     */
    public function getAuthIdentifierColumn()
    {
        return defined('static::AUTH_IDENTIFIER')
            ? static::AUTH_IDENTIFIER
            : 'email';
    }

    /**
     * @return string
     */
    public function getAuthPasswordColumn()
    {
        return defined('static::AUTH_PASSWORD')
            ? static::AUTH_PASSWORD
            : 'password';
    }

    /**
     * Try to login
     *
     * @param $identifier
     * @param $password
     * @return $this
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    protected function attemptLogin($identifier, $password)
    {
        $found = $this->getRow([
            $this->getAuthIdentifierColumn() => $identifier,
        ]);

        if(empty($found)) {
            $this->reason_not_logged = 'not_found';
            return $this;
        }

        if(!check_password($password, $found[$this->getAuthPasswordColumn()])) {
            $this->reason_not_logged = 'bad_password';
            return $this;
        }

        $this->setAuth(static::model($found)->getAttributes());

        return $this;
    }

    protected function setRememberMe()
    {
        $value = hash('sha256', $this->getId());
        $token = token(250);

        setcookie("remember_me", $value, time()+ (30 * 24 * 60 * 60 * 1000));
        setcookie("remember_token", $token, time()+ (30 * 24 * 60 * 60 * 1000));
    }
}