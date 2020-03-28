<?php

namespace Rseon\Mallow;

class Registry
{

    protected $registry = [];

    protected static $instance;
    public static function getInstance() {
        if (!(static::$instance instanceof static)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Add a key to the registry
     *
     * @param string $key
     * @param $value
     * @return $this
     */
    public function set(string $key, $value)
    {
        $this->registry[$key] = $value;
        return $this;
    }

    /**
     * Get key from registry
     *
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return $this->registry[$key] ?? $default;
    }

    /**
     * Get if key exists in registry
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key)
    {
        return array_key_exists($key, $this->registry);
    }

    /**
     * Add $data to existing $key
     *
     * @param string $key
     * @param mixed $data
     * @return $this
     */
    public function add(string $key, $data)
    {
        if(!$this->has($key)) {
            $this->set($key, []);
        }

        $this->registry[$key][] = $data;
    }
}