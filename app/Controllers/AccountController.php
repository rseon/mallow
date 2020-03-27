<?php

namespace App\Controllers;

use App\Models\User;
use Rseon\Mallow\Controller;

class AccountController extends Controller
{

    /**
     * Authenticated user
     * @var User
     */
    protected $user;

    /**
     * Must be authenticated
     */
    public function init()
    {
        $this->user = new User;
        if(!$this->user->isAuth()) {
            redirect(route('login'));
        }

        $this->layout('layouts.account', [
            'user' => $this->user->getAuth(),
        ]);
    }

    /**
     * @param array $request
     */
    public function index(array $request)
    {
        $this->setTitle("My account");
        $this->view('account.index');
    }
}