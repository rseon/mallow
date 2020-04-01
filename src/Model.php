<?php

namespace Rseon\Mallow;

use Rseon\Mallow\Exceptions\ModelException;
use Rseon\Mallow\Exceptions\DatabaseException;

/**
 * @method array getAll($conditions = [], $sort = [], $limit = null, $only_fields = '*')
 * @method array getRow($conditions = [], $only_fields = null)
 * @method array getValue($fieldname, $conditions = [])
 * @method array fetchAll($sql, $datas = [])
 * @method array fetchRow($sql, $datas = [])
 * @method int insert(array $datas)
 * @method int update(array $datas, array $conditions)
 * @method int delete(array $conditions = [])
 * @method array routine($name, $params = [])
 */
abstract class Model
{
    const HOOK_BEFORE = 'before';
    const HOOK_AFTER = 'after';

    protected $table;
    protected $primary = 'id';
    protected $id;
    protected $attributes = [];
    protected $found;
    protected $cast = [];
    protected $hooks = [];
    protected $hidden = [];
    protected $required = [];
    protected $validate = [];
    protected $messages = [];
    protected $show_hidden = false;

    /**
     * Find a model by its id
     *
     * @param int|array $primary
     * @return static
     */
    public static function find($primary)
    {
        return static::execFind(new static, $primary);
    }

    /**
     * Find a model by its id or returns exception if not found
     *
     * @param $primary
     * @return static
     * @throws ModelException
     */
    public static function findOrFail($primary)
    {
        $self = static::find($primary);
        if(!$self->found()) {
            $class = static::class;
            $message = "Model {$class}";
            if(!is_array($primary)) {
                $message .= " with primary {$primary}";
            }
            $message .= " not found.";
            throw new ModelException($message);
        }
        return $self;
    }

    /**
     * Retrieve all models
     *
     * @param array $conditions
     * @param array $sort
     * @param null $limit
     * @param string $only_fields
     * @return static
     * @throws \Exception
     */
    public static function all(array $conditions = [], array $sort = [], $limit = null, $only_fields = '*')
    {
        return static::model((new static)->getAll($conditions, $sort, $limit, $only_fields));
    }


    /**
     * Transform database result as object
     *
     * @param $data
     * @return static
     * @throws \Exception
     */
    public static function model($data = null)
    {
        if(!$data) {
            $instance = new static;
            $instance->setFound(false);
            return $instance;
        }

        if(array_key_exists(0, $data))
        {
            foreach($data as $k => $v) {
                $data[$k] = static::model($v);
            }

            return $data;
        }

        $instance = new static;
        $instance->setAttributes($data);

        $primary = $instance->primary;
        if(isset($data[$primary])) {
            $instance->setId($data[$primary]);
            $instance->setFound(true);
        }

        return $instance;
    }

    /**
     * Model constructor.
     * @param null $id
     * @throws Exceptions\AppException
     */
    public function __construct($id = null)
    {
        if(!array_key_exists($this->primary, $this->cast)) {
            $this->cast[$this->primary] = 'int';
        }

        $class = static::class;

        if(!$this->table) {
            $this->table = normalize_string($class);
        }

        foreach(class_uses($class) as $trait) {
            $trait = basename(str_replace('\\', '/', $trait));
            $initMethod = 'init'.$trait.'Trait';
            if(method_exists($class, $initMethod)) {
                $this->{$initMethod}();
            }
        }

        if($id) {
            return static::execFind($this, $id);
        }
    }

    /**
     * Add a hook to this model
     *
     * @param $hookname
     * @param $callback
     * @param null $position
     * @throws ModelException
     */
    public function addHook($hookname, $callback, $position = null)
    {
        if(!array_key_exists($hookname, $this->hooks)) {
            $this->hooks[$hookname] = [];
        }
        if(!$position) {
            $position = sizeof($this->hooks[$hookname]);
        }
        if(array_key_exists($position, $this->hooks[$hookname])) {
            throw new ModelException("Model $hookname hook already set at the position $position.");
        }
        $this->hooks[$hookname][$position] = $callback;
        ksort($this->hooks[$hookname]);
    }

    /**
     * Call a method from Database
     *
     * @param string $method_name
     * @param array $args
     * @return mixed
     * @throws ModelException
     */
    public function __call(string $method_name, array $args) {
        if(!$this->table) {
            throw new ModelException('Model table name is missing');
        }
        if(!$this->primary) {
            throw new ModelException('Model primary key name is missing');
        }

        // Launch hook before
        $before = $this->launchHooksBefore($method_name, $args);
        if(!is_null($before)) {
            return $before;
        }

        // Add model table name
        if(in_array($method_name, ['getAll', 'getRow', 'getValue', 'insert', 'update', 'delete'])) {
            array_unshift($args, $this->table);
        }

        try {
            registry('Debugbar')['time']->startMeasure('Model', $this->getMethod($method_name));
            $data = registry('Database')->$method_name(...$args);
            registry('Debugbar')['time']->stopMeasure('Model');

            // Launch hook after
            $this->launchHooksAfter($data);

            return $data;
        }
        catch(DatabaseException $e) {
            throw new ModelException($e->getMessage());
            registry('Debugbar')['exceptions']->addException($e);
            return null;
        }
    }

    /**
     * Set attribute
     *
     * @param $name
     * @param $value
     * @return $this
     */
    public function __set($name, $value)
    {
        return $this->setAttribute($name, $value);
    }

    /**
     * Get attribute
     *
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    /**
     * Model is found
     *
     * @return mixed
     */
    public function found()
    {
        return $this->found;
    }

    /**
     * Set if model is found
     *
     * @param bool $flag
     * @return $this
     */
    public function setFound(bool $flag)
    {
        $this->found = $flag;
        return $this;
    }

    /**
     * Set attributes
     *
     * @param array $data
     * @return $this
     * @throws \Exception
     */
    public function setAttributes(array $data)
    {
        foreach($data as $name => $value) {
            if(!$this->show_hidden && in_array($name, $this->hidden)) {
                continue;
            }
            $this->setAttribute($name, $value);
        }

        return $this;
    }

    /**
     * Get attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = $this->attributes;
        foreach($attributes as $k => $v) {
            $attributes[$k] = $this->getAttribute($k);
        }

        return $attributes;
    }

    /**
     * Set an attribute
     *
     * @param $name
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $this->castAttribute($name, $value);
        return $this;
    }

    /**
     * Get an attribute
     *
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    public function getAttribute($name, $default = null)
    {
        if(!array_key_exists($name, $this->attributes)) {
            return $default;
        }

        if(gettype($this->attributes[$name]) === 'object') {
            switch(get_class($this->attributes[$name])) {
                case 'DateTime':
                    return $this->attributes[$name]->format('Y-m-d H:i:s');
            }
        }
        return $this->attributes[$name];
    }

    /**
     * Set model id
     *
     * @param $id
     * @return $this
     * @throws \Exception
     */
    public function setId($id)
    {
        $id = $this->castAttribute($this->primary, $id);
        $this->id = $id;
        $this->setAttribute($this->primary, $id);
        return $this;
    }

    /**
     * Cast an attribute
     *
     * @param string $attribute
     * @param $value
     * @return \DateTime|int
     * @throws \Exception
     */
    public function castAttribute(string $attribute, $value)
    {
        if(array_key_exists($attribute, $this->cast)) {
            switch($this->cast[$attribute]) {
                case 'int':
                    return (int) $value;
                case 'datetime':
                    if(!$value) {
                        return $value;
                    }
                    return new \DateTime($value);
                case class_exists($this->cast[$attribute]) && method_exists($this->cast[$attribute], 'format'):
                    return $this->cast[$attribute]::format($value);
                    break;
            }
        }
        return $value;
    }

    /**
     * Save the model.
     * If has id, update it, else insert
     *
     * @return $this
     * @throws Exceptions\AppException
     * @throws ModelException
     */
    public function save()
    {
        $data = $this->getAttributes();
        $class = static::class;

        if($errors = $this->validate($data)) {
            throw new ModelException("Attributes are incorrect.");
        }

        /*// Check required
        foreach($this->required as $r) {
            if(!isset($data[$r]) || !$data[$r]) {
                throw new ModelException("Field '{$r}' is required to save model {$class}.");
            }
        }

        // Check validate
        foreach($this->validate as $field => $validator) {
            if(isset($data[$field]) && !$validator::validate($data[$field])) {
                throw new ModelException("Field '{$field}' is not valid to save model {$class}.");
            }
        }*/

        if($this->id) {
            $this->update($data, [
                $this->primary => $this->id,
            ]);
        }
        else {
            $this->id = $this->insert($data);
        }

        return new static($this->id);
    }

    /**
     * Validate the data
     *
     * @param array $data
     * @return array
     */
    public function validate(array $data)
    {
        $errors = [];
        // Check required
        foreach($this->required as $r) {
            if(!isset($data[$r]) || !$data[$r]) {
                $errors[$r][] = $this->messages[$r.'.required'] ?? "The field {$r} is required";
            }
        }

        // Check validate
        foreach($this->validate as $field => $validator) {
            if(isset($data[$field]) && !$validator::validate($data[$field])) {
                $errors[$field][] = $this->messages[$field.'.validate'] ?? "The field {$field} is not valid";
            }
        }
        return $errors;
    }

    /**
     * Delete the model
     *
     * @param array $conditions
     * @return int|null
     */
    public function delete(array $conditions = [])
    {
        if($this->id) {
            $conditions[$this->primary] = $this->id;
        }

        if(empty($conditions)) {
            return 0;
        }

        // Launch hook before
        $args = [$conditions];
        $before = $this->launchHooksBefore('delete', $args);
        if(!is_null($before)) {
            return $before;
        }

        return registry('Database')->delete($this->table, $conditions);
    }

    /**
     * Returns if $instance is the same as current model
     *
     * @param $instance
     * @return bool
     */
    public function is($instance)
    {
        return $this->table === $instance->table
            && $this->primary === $instance->primary
            && $this->{$this->primary} === $instance->{$instance->primary};
    }

    /**
     * Returns if $instance is different as current model
     *
     * @param $instance
     * @return bool
     */
    public function isNot($instance)
    {
        return !$this->is($instance);
    }

    public function showHidden($flag = false)
    {
        $this->show_hidden = $flag;
    }

    /**
     * Get called method
     *
     * @param string $method
     * @return string
     */
    protected function getMethod(string $method)
    {
        return static::class.'::'.$method;
    }

    /**
     * Find a model
     *
     * @param $instance
     * @param array|int $primary
     * @return mixed
     */
    protected static function execFind($instance, $primary)
    {
        if(is_array($primary)) {
            $condition = $primary;
        }
        else {
            $condition = [
                $instance->primary => $primary
            ];
        }
        $data = $instance->getRow($condition);

        $found = false;
        if($data) {
            $found = true;
            $instance->setId($data[$instance->primary]);
            $instance->setAttributes($data);
        }
        $instance->setFound($found);

        return $instance;
    }

    /**
     * Method launched before SQL request.
     * If returns something different of null, this result will be returned and query will not be executed
     *
     * @param string $method_name
     * @param array $args
     * @return null
     */
    protected function launchHooksBefore(string $method_name, array &$args)
    {
        if(array_key_exists(static::HOOK_BEFORE, $this->hooks)) {
            foreach($this->hooks[static::HOOK_BEFORE] as $position => $callback) {
                $res = call_user_func_array($callback, [$method_name, &$args]);
                if(!is_null($res)) {
                    return $res;
                }
            }
        }

        return null;
    }

    /**
     * Method launched after SQL request.
     * Can manipulate data before return it.
     *
     * @param $data
     * @return mixed
     */
    protected function launchHooksAfter(&$data)
    {
        if(array_key_exists(static::HOOK_AFTER, $this->hooks)) {
            foreach ($this->hooks[static::HOOK_AFTER] as $position => $callback) {
                call_user_func_array($callback, [&$data]);
            }
        }
    }
}