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
        if($data === false) {
            session_destroy();
            $this->unsetRememberMe();
        }
        $_SESSION[$this->getAuthSessionName()] = $data;
        return $this;
    }

    /**
     * Get authenticate data
     *
     * @param string|null $key
     * @return bool|mixed|null
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function getAuth(string $key = null)
    {
        if(!array_key_exists($this->getAuthSessionName(), $_SESSION)) {
            return false;
        }

        if($key) {
            return $_SESSION[$this->getAuthSessionName()][$key] ?? null;
        }

        return $_SESSION[$this->getAuthSessionName()];
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
    public function getAuthRememberTokenColumn()
    {
        return defined('static::AUTH_REMEMBER_TOKEN')
            ? static::AUTH_REMEMBER_TOKEN
            : 'remember_token';
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
     * @return bool
     */
    public function checkRememberMe()
    {
        if(!array_key_exists('remember_me', $_COOKIE) || !array_key_exists('remember_token', $_COOKIE)) {
            return false;
        }

        $check = $this->getRow([
            $this->getAuthRememberTokenColumn() => sanitize($_COOKIE['remember_token']),
        ]);

        if(!$check) {
            return false;
        }

        if($_COOKIE['remember_me'] !== make_hash($check['id'])) {
            return false;
        }

        return static::model($check);
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

        $hidden = $this->hidden;
        $found = array_filter($found, function($key) use ($hidden) {
            return !in_array($key, $hidden);
        }, ARRAY_FILTER_USE_KEY);

        $this->setAuth($found);

        return $this;
    }

    /**
     * Set the "remember me" cookie
     *
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    protected function setRememberMe()
    {
        $value = make_hash($this->getAuth('id'));
        $token = token(250);

        setcookie("remember_me", $value, time()+(60*60*24*30), '/', getenv('APP_DOMAIN'));
        setcookie("remember_token", $token, time()+(60*60*24*30), '/', getenv('APP_DOMAIN'));

        $this->update([
            $this->getAuthRememberTokenColumn() => $token,
        ], [
            $this->primary => $this->getAuth('id'),
        ]);
    }

    /**
     * Unset the "remember me" token
     */
    protected function unsetRememberMe()
    {
        setcookie("remember_me", '', time()-(60*60*24*30), '/', getenv('APP_DOMAIN'));
        setcookie("remember_token", '', time()-(60*60*24*30), '/', getenv('APP_DOMAIN'));

        $this->update([
            $this->getAuthRememberTokenColumn() => null,
        ], [
            $this->primary => $this->getAuth('id'),
        ]);
    }
}