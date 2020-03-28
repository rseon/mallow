<?php

namespace Rseon\Mallow\Models\Traits;

/**
 * Trait Cache
 * @package Rseon\Mallow\Traits\Model
 *
 * This trait permit to cache multiple Database results for same queries.
 * When using this trait, che is enabled by default, but you can enable or disable it using
 * $this->disableCache() or $this->>enableCache().
 */
trait Cache
{
    protected $cache_hash;
    protected $cache_enabled = true;

    /**
     * Initialize the trait
     */
    public function initCacheTrait()
    {
        $this->addHook(static::HOOK_BEFORE, [$this, 'cacheHookBefore']);
        $this->addHook(static::HOOK_AFTER, [$this, 'cacheHookAfter']);
    }

    /**
     * Enable the cache
     */
    public function enableCache()
    {
        $this->cache_enabled = true;
        return $this;
    }

    /**
     * Disable the cache
     */
    public function disableCache()
    {
        $this->cache_enabled = false;
        return $this;
    }

    /**
     * Check the cache before launch request
     *
     * @param string $method_name
     * @param array $args
     * @return |null
     */
    protected function cacheHookBefore(string $method_name, array $args)
    {
        if(!$this->checkEnabled()) {
            return null;
        }

        $this->cache_hash = hash('sha256', $this->getMethod($method_name).json_encode($args));
        return $this->checkCache();
    }

    /**
     * Save result in cache
     *
     * @param $data
     * @return mixed
     */
    protected function cacheHookAfter($data)
    {
        if(!$this->checkEnabled()) {
            return $data;
        }

        $registry = registry();
        $cache = registry()->get('ModelCache');
        $cache[$this->cache_hash] = $data;
        registry()->set('ModelCache', $cache);

        return $data;
    }

    /**
     * Check if cache is enabled
     *
     * @return bool
     */
    protected function checkEnabled()
    {
        return $this->cache_enabled;
    }

    /**
     * Check result in cache
     *
     * @return |null
     */
    protected function checkCache()
    {
        $registry = registry();
        if(!$registry->has('ModelCache')) {
            $registry->set('ModelCache', []);
        }
        $cache = $registry->get('ModelCache');
        if(array_key_exists($this->cache_hash, $cache)) {
            return $cache[$this->cache_hash];
        }
        return null;
    }
}