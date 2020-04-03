<?php

namespace Rseon\Mallow;

use Rseon\Mallow\Exceptions\ViewException;

class View
{
    protected $args = [];
    protected $path;
    protected $content;

    /**
     * View constructor.
     *
     * @param string $path
     * @param array $args
     */
    public function __construct(string $path, array $args = [])
    {
        $this->path = $path;
        $this->assign($args);
    }

    /**
     * Get path of a view
     *
     * @param string $path
     * @return string
     * @throws Exceptions\AppException
     */
    public function getPath(string $path)
    {
        $path = str_replace('.', '/', $path);
        return get_path(config('views_path')."/$path.php");
    }

    /**
     * @param $key
     * @param null $value
     */
    public function __set($key, $value = null)
    {
        $this->assign($key, $value);
    }

    /**
     * @param $key
     * @return bool|mixed
     * @throws Exceptions\AppException
     */
    public function __get($key)
    {
        if(!array_key_exists($key, $this->args)) {
            return trigger_error("Undefined view argument $key in {$this->getPath($this->path)}.");
        }
        return $this->args[$key];
    }

    /**
     * @param $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->args[$key]);
    }

    /**
     * @param $key
     */
    public function __unset($key)
    {
        unset($this->args[$key]);
    }

    /**
     * Assign data to a view
     *
     * @param $key
     * @param null $value
     * @return $this
     */
    public function assign($key, $value = null)
    {
        if(is_array($key)) {
            foreach($key as $k => $v) {
                $this->assign($k, $v);
            }
        }
        else {
            $this->args[$key] = $value;
        }

        return $this;
    }

    /**
     * Get content of a layout
     *
     * @return |null
     */
    public function content()
    {
        if(array_key_exists('content', $this->args)) {
            return $this->args['content']->render();
        }
        return null;
    }

    /**
     * Render the view
     *
     * @return false|string
     * @throws Exceptions\AppException
     */
    public function __toString()
    {
        try {
            return $this->render();
        }
        catch (ViewException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Include the view
     *
     * @throws Exceptions\AppException
     * @throws ViewException
     */
    public function render()
    {
        if($this->content) {
            return $this->content;
        }

        $file = $this->getPath($this->path);
        if(file_exists($file)) {
            if(debug()->isEnabled()) {
                debug()->getCollector('views')->addView([
                    'path' => $this->path,
                    'args' => $this->args,
                ]);
            }

            registry()->add('views', $this->path);

            //include $file;
            ob_start();
            include $file;
            $this->content = ob_get_clean();
            return $this->content;
        }
        else {
            $_message = "View file {$this->path} not found";
            $exception = new ViewException($_message);
            if(debug()->isEnabled()) {
                debug()->getCollector('messages')->error($_message);
                debug()->getCollector('exceptions')->addException($exception);
            }
            throw $exception;
        }
    }

    /**
     * Create subview
     *
     * @param string $path
     * @param array $args
     * @return false|string
     * @throws Exceptions\AppException
     * @throws ViewException
     */
    public function partial(string $path, array $args = [])
    {
        return new static($path, $args);
    }

    /**
     * Returns view attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->args;
    }
}