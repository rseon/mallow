<?php

namespace Rseon\Mallow;

abstract class Controller
{
    const DEFAULT_LAYOUT = 'layouts.app';

    protected $current;
    protected $action;
    protected $layout;
    protected $view;
    protected $request;

    /**
     * Controller constructor.
     *
     * @param string|null $action
     * @param array $request
     * @throws Exceptions\AppException
     */
    public function __construct(string $action = null, array $request = [])
    {
        $this->current = normalize_string(static::class);
        $this->request = $request;

        if($action) {
            $this->action = normalize_string($action);
        }

        if(method_exists($this, 'init')) {
            $this->init();
        }
    }

    /**
     * Get action
     *
     * @return false|string|string[]
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Run the controller
     *
     * @throws Exceptions\AppException
     */
    public function run()
    {
        if(is_xhr()) {
            if(!array_key_exists('Content-Type', headers_list())) {
                header('Content-Type: application/json');
            }
            return;
        }

        if($this->view === false) {
            return;
        }

        if(!$this->view) {
            $view_path = $this->current.'.'.$this->action;
            $view_path = str_replace('_', '.', $view_path);
            $this->view($view_path);
        }

        if($this->layout === false) {
            return $this->view->render();
        }

        if(!$this->layout) {
            $this->layout(static::DEFAULT_LAYOUT);
        }

        $this->layout->assign('content', $this->view);
        return $this->layout->render();
    }

    /**
     * Set the layout
     *
     * @param string $layout
     * @return View
     */
    public function layout(string $layout, array $args = [])
    {
        $this->layout = view($layout, $args);
        return $this->layout;
    }

    /**
     * Get the layout
     *
     * @return mixed|View
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Disable the layout
     */
    public function disableLayout()
    {
        $this->layout = false;
    }

    /**
     * Set the view with its arguments
     *
     * @param string $path
     * @param array $args
     * @return View
     */
    public function view(string $path, array $args = [])
    {
        $this->view = view($path, $args);
        return $this->view;
    }

    /**
     * Get the view
     *
     * @return mixed|View
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Disable the view
     */
    public function disableView()
    {
        $this->view = false;
        $this->disableLayout();
    }

    /**
     * Set the page title
     *
     * @param string $title
     */
    public function setTitle(string $title)
    {
        return set_meta('title', __($title, [], 'meta'));
    }

    /**
     * Check CSRF from $data (default is $_POST) and redirect if incorrect
     *
     * @param string $redirect
     * @param null $data
     */
    public function csrf(string $redirect, $data = null)
    {
        if(is_null($data)) {
            $data = $_POST;
        }

        if(!check_csrf_array($data)) {
            old($data);
            flash()->warning('Incorrect CSRF token');
            redirect($redirect);
        }

        csrf()->resetToken();
    }

    /**
     * Get request
     *
     * @param null $key
     * @param null $default
     * @return array|mixed|null
     */
    public function request($key = null, $default = null)
    {
        $data = $default;
        if(!$key) {
            $data = $this->request['request'];
        }
        else {
            $data = $this->request['request'][$key] ?? $default;
        }

        return sanitize($data);
    }
}