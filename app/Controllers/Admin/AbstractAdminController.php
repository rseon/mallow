<?php

namespace App\Controllers\Admin;

use App\Models\Admin\User;
use Rseon\Mallow\Controller;
use App\Traits\Admin\AdminUtils;

abstract class AbstractAdminController extends Controller
{
    use AdminUtils;

    /**
     * Authenticated user
     *
     * @var User
     */
    protected $user;

    /**
     * Remembered user
     *
     * @var User|bool
     */
    protected $remember;

    /**
     * Check if user is logged and apply basic initialization
     *
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function init()
    {
        $this->user = new User;

        // If not logged
        if(!$this->user->isAuth()) {

            // Check the "remember me"
            $this->remember = $this->user->checkRememberMe();
            if($this->remember !== false && !in_array($this->getAction(), ['login', 'logout', 'lockscreen'])) {
                // Save current URL to be redirected to after login
                $_SESSION['back_login'] = $_SERVER['REQUEST_URI'];

                redirect(admin_url('/auth/lockscreen'));
            }

            // Redirect
            if(!in_array($this->getAction(), ['login', 'logout', 'lockscreen'])) {
                // Save current URL to be redirected to after login
                $_SESSION['back_login'] = $_SERVER['REQUEST_URI'];

                redirect(admin_url('/auth/login'));
            }

            return;
        }

        $this->layout('layouts.admin', [
            'user' => $this->user->getAuth(),
            'layout_active_menu' => null,
        ]);
        $this->setTitle("Administration");
    }

    /**
     * Add path to the view
     *
     * @param string $path
     * @param array $args
     * @return \Rseon\Mallow\View
     */
    public function view(string $path, array $args = [])
    {
        return parent::view("admin.$path", $args);
    }

    /**
     * Add path to the layout
     *
     * @param string $layout
     * @param array $args
     * @return \Rseon\Mallow\View
     */
    public function layout(string $layout, array $args = [])
    {
        return parent::layout("admin.$layout", $args);
    }

    /**
     * Set the page title
     *
     * @param string $title
     */
    public function setTitle(string $title)
    {
        return parent::setTitle($title . ' | Administration');
    }
}