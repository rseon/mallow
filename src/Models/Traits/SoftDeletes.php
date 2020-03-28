<?php

namespace Rseon\Mallow\Models\Traits;

/**
 * Trait SoftDeletes
 * @package Rseon\Mallow\Traits\Model
 *
 *
 */
trait SoftDeletes
{
    protected $with_trashed = false;
    protected $force_delete = false;

    /**
     * Initialize the trait
     */
    public function initSoftDeletesTrait()
    {
        $this->cast[$this->getDeletedAtColumn()] = 'datetime';
        $this->addHook(static::HOOK_BEFORE, [$this, 'softDeletesHookBefore']);
    }

    /**
     * Get deleted_at column
     *
     * @return string
     */
    public function getDeletedAtColumn()
    {
        return defined('static::DELETED_AT')
            ? static::DELETED_AT
            : 'deleted_at';
    }

    /**
     * Returns deleted rows
     *
     * @param bool $flag
     * @return $this
     */
    public function withTrashed($flag = true)
    {
        $this->with_trashed = $flag;
        return $this;
    }

    /**
     * Hard delete
     *
     * @param array $conditions
     * @return mixed
     */
    public function forceDelete(array $conditions)
    {
        $this->force_delete = true;
        $data = $this->delete($conditions);
        $this->force_delete = false;
        return $data;
    }

    /**
     * Restore deleted
     *
     * @param array $conditions
     * @return mixed
     */
    public function restore(array $conditions)
    {
        return $this->update([
            $this->getDeletedAtColumn() => null,
        ], $conditions);
    }

    /**
     * Check if is hard delete
     *
     * @return bool
     */
    public function isForceDelete()
    {
        return $this->force_delete;
    }

    /**
     * @param string $method_name
     * @param array $args
     * @return mixed
     */
    protected function softDeletesHookBefore(string $method_name, array &$args)
    {
        switch($method_name) {
            case 'getAll':
            case 'getRow':
            case 'getValue':
                // Add condition deleted_at IS NULL
                if(!$this->with_trashed) {
                    $args[0][] = $this->getDeletedAtColumn() . ' IS NULL';
                }

                break;
            case 'delete':
                // Update deleted_at instead of hard delete
                if(!$this->isForceDelete()) {
                    $args[0][] = $this->getDeletedAtColumn() . ' IS NULL';
                    return $this->update([
                        $this->getDeletedAtColumn() => date('Y-m-d H:i:s'),
                    ], $args[0]);
                }
                break;
        }
    }
}