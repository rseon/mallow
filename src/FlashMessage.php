<?php

namespace Rseon\Mallow;

class FlashMessage
{
    const INFO    = 'info';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const ERROR   = 'danger';

    const SESSION_NAME = 'flash_messages';

    protected static $instance;
    public static function getInstance() {
        if (!(static::$instance instanceof static)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Add info message
     *
     * @param string|array $message
     * @return $this
     */
    public function info($message)
    {
        return $this->add(static::INFO, $message);
    }

    /**
     * Add success message
     *
     * @param string|array $message
     * @return $this
     */
    public function success($message)
    {
        return $this->add(static::SUCCESS, $message);
    }

    /**
     * Add warning message
     *
     * @param string|array $message
     * @return $this
     */
    public function warning($message)
    {
        return $this->add(static::WARNING, $message);
    }

    /**
     * Add error message
     *
     * @param string|array $message
     * @return $this
     */
    public function error($message)
    {
        return $this->add(static::ERROR, $message);
    }

    /**
     * Add message
     *
     * @param string $type
     * @param string|array $message
     * @return $this
     */
    public function add(string $type, $message)
    {
        return $this->addSession($type, $message);
    }

    /**
     * Get message by type or all messages
     *
     * @param string|null $type
     * @return array|mixed
     */
    public function get(string $type = null)
    {
        return $this->getSession($type);
    }

    /**
     * Get if has info message
     *
     * @return bool
     */
    public function hasInfo()
    {
        return $this->has(static::INFO);
    }

    /**
     * Get if has success message
     *
     * @return bool
     */
    public function hasSuccess()
    {
        return $this->has(static::SUCCESS);
    }

    /**
     * Get if has warning message
     *
     * @return bool
     */
    public function hasWarning()
    {
        return $this->has(static::WARNING);
    }

    /**
     * Get if has error message
     *
     * @return bool
     */
    public function hasError()
    {
        return $this->has(static::ERROR);
    }

    /**
     * Get if has messages by type or all
     *
     * @param string|null $type
     * @return bool
     */
    public function has(string $type = null)
    {
        return $this->hasSession($type);
    }

    /**
     * Get if no message by type or all
     *
     * @param string|null $type
     * @return bool
     */
    public function isEmpty(string $type = null)
    {
        return count($this->getSession($type)) === 0;
    }

    /**
     * Clear messages by type or all
     *
     * @param string|null $type
     * @return $this
     */
    public function clear(string $type = null)
    {
        return $this->clearSession($type);
    }

    /**
     * FlashMessage constructor.
     */
    protected function __construct()
    {
        if (!array_key_exists(static::SESSION_NAME, $_SESSION)) {
            $_SESSION[static::SESSION_NAME] = [];
        }
    }

    /**
     * Init the session
     *
     * @return $this
     */
    protected function initSession()
    {
        unset($_SESSION[static::SESSION_NAME]);
        $_SESSION[static::SESSION_NAME] = [];

        return $this;
    }

    /**
     * Add message to session
     *
     * @param string $type
     * @param string|array $message
     * @return $this
     */
    protected function addSession(string $type, $message)
    {
        $type = strtolower($type);
        if(!array_key_exists($type, $_SESSION[static::SESSION_NAME])) {
            $_SESSION[static::SESSION_NAME][$type] = [];
        }

        if(is_array($message)) {
            foreach($message as $k => $m) {
                $_SESSION[static::SESSION_NAME][$type][$k] = $m;
            }
        }
        else {
            $_SESSION[static::SESSION_NAME][$type][] = $message;
        }

        return $this;
    }

    /**
     * Get from session by type or all
     *
     * @param string|null $type
     * @return array|mixed
     */
    protected function getSession(string $type = null)
    {
        $type = strtolower($type);
        $messages = [];
        if(!$type) {
            $messages = $_SESSION[static::SESSION_NAME];
        }
        elseif(is_array($type)) {
            foreach($type as $t) {
                $messages[] = $this->getSession($t);
            }
        }
        elseif($this->hasSession($type)) {
            $messages = $_SESSION[static::SESSION_NAME][$type];
        }

        return $messages;
    }

    /**
     * Check if has message is session by type or all
     *
     * @param string|null $type
     * @return bool
     */
    protected function hasSession(string $type = null)
    {
        $type = strtolower($type);
        if(!$type) {
            return !empty($_SESSION[static::SESSION_NAME]);
        }

        return !empty($_SESSION[static::SESSION_NAME][$type]);
    }

    /**
     * Clear session messages by type or all
     *
     * @param string|null $type
     * @return $this
     */
    protected function clearSession(string $type = null)
    {
        $type = strtolower($type);
        if(!$type) {
            return $this->initSession();
        }
        unset($_SESSION[static::SESSION_NAME][$type]);
        return $this;
    }
}