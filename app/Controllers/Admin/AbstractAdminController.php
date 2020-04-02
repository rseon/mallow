<?php

namespace App\Controllers\Admin;

use App\Models\Admin\User;
use Rseon\Mallow\Controller;
use App\Traits\Admin\AdminUtils;

abstract class AbstractAdminController extends Controller
{
    use AdminUtils;

    /**
     * @var User
     */
    protected $user;

    /**
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function init()
    {
        $this->user = new User;

        // Redirect if not logged
        if(!in_array($this->getAction(), ['login', 'logout']) && !$this->user->isAuth()) {
            redirect(admin_url('/auth/login'));
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