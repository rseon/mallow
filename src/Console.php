<?php

namespace Rseon\Mallow;

use Rseon\Mallow\Exceptions\ConsoleException;

class Console
{
    protected $command;
    protected $args;

    /**
     * Console constructor.
     * @param array $args
     * @throws ConsoleException
     * @throws Exceptions\AppException
     */
    public function __construct(array $args)
    {
        array_shift($args);

        if(!$args) {
            throw new ConsoleException("No command provided.");
        }

        $this->command = array_shift($args);
        $this->args = $args;
    }

    /**
     * @return mixed
     * @throws ConsoleException
     * @throws Exceptions\AppException
     */
    public function run()
    {
        $action = null;
        if(strpos($this->command, ':') !== false) {
            list($this->command, $action) = explode(':', $this->command);
        }

        $className = normalize_string_reverse($this->command, 'controller');
        $action = normalize_string_reverse($action, 'action');
        $class = 'Rseon\\Mallow\\Command\\'.$className;
        if(!class_exists($class)) {
            $class = 'App\\Command\\'.$className;

            if(!class_exists($class)) {
                throw new ConsoleException("Command {$className} not found.");
            }
        }

        $instance = new $class($this->command, $this->args);

        if(!method_exists($instance, 'handle')) {
            throw new ConsoleException("No handle method in {$class}.");
        }

        $instance->handle($action);
        return $instance->out();
    }
}