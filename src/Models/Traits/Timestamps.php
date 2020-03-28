<?php

namespace Rseon\Mallow\Models\Traits;


trait Timestamps
{
    /**
     * Initialize the trait
     */
    public function initTimestampsTrait()
    {
        $this->cast[$this->getCreatedAtColumn()] = 'datetime';
        $this->cast[$this->getUpdatedAtColumn()] = 'datetime';

        $this->addHook(static::HOOK_BEFORE, [$this, 'timestampsHookBefore']);
    }

    /**
     * Get created_at column
     *
     * @return string
     */
    public function getCreatedAtColumn()
    {
        return defined('static::CREATED_AT')
            ? static::CREATED_AT
            : 'created_at';
    }

    /**
     * Get updated_at column
     *
     * @return string
     */
    public function getUpdatedAtColumn()
    {
        return defined('static::UPDATED_AT')
            ? static::UPDATED_AT
            : 'updated_at';
    }

    /**
     * @param string $method_name
     * @param array $args
     * @return mixed
     */
    protected function timestampsHookBefore(string $method_name, array &$args)
    {
        switch($method_name) {
            case 'insert':
                $args[0][$this->getCreatedAtColumn()] = date('Y-m-d H:i:s');
                break;
            case 'update':
                $args[0][$this->getUpdatedAtColumn()] = date('Y-m-d H:i:s');
                break;
            case 'delete':
                if(method_exists($this, 'isForceDelete') && !$this->isForceDelete()) {
                    $args[0][$this->getUpdatedAtColumn()] = date('Y-m-d H:i:s');
                }
                break;
        }
    }
}