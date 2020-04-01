<?php

namespace App\Controllers;

use App\Models\User;
use Rseon\Mallow\Controller;

class AuthController extends Controller
{
    /**
     * Redirect to this route when authenticated
     * @var string
     */
    protected $route_auth = 'account';

    /**
     * @var User
     */
    protected $user;

    /**
     * Redirect to account if logged
     */
    public function init()
    {
        $this->user = new User;
        if(in_array($this->getAction(), ['login', 'register'])) {
            if($this->user->isAuth()) {
                redirect(route($this->route_auth));
            }
        }
    }

    /**
     * Sign in user
     *
     * @param array $request
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function login()
    {
        // Check login
        if(is_post()) {
            $this->csrf(route('login'));

            $post = $this->request();
            $errors = [];
            if(!isset($post['email']) || $post['email'] === '') {
                $errors['email'] = 'Please fill in your email';
            }
            if(!isset($post['password']) || $post['password'] === '') {
                $errors['password'] = 'Please fill in your password';
            }

            if($errors) {
                old($post);
                flash()->error($errors);
                redirect(route('login'));
            }

            $this->user->auth($post['email'], $post['password']);
            if(!$this->user->isAuth()) {
                $reason = $this->user->getReason();
                switch($reason) {
                    case 'not_found':
                        $errors[] = 'User not found';
                        break;
                    case 'bad_password':
                        $errors[] = 'Bad password';
                        break;
                }

                old($post);
                flash()->error($errors);
                redirect(route($this->route_auth));
            }

            flash()->success('Logged with success !');
            redirect(route('account'));
        }

        $this->view('auth.login');
    }

    /**
     * Sign out user
     *
     * @param array $request
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function logout()
    {
        if($this->user->isAuth()) {
            $this->user->setAuth(false);
        }

        redirect(route('index'));
    }

    /**
     * Sign up user
     *
     * @throws \Rseon\Mallow\Exceptions\AppException
     * @throws \Rseon\Mallow\Exceptions\ModelException
     */
    public function register()
    {
        // Check login
        if(is_post()) {
            $this->csrf(route('register'));

            $post = $this->request();
            $errors = [];
            if(!isset($post['name']) || $post['name'] === '') {
                $errors['name'] = 'Please fill in your name';
            }
            if(!isset($post['email']) || $post['email'] === '') {
                $errors['email'] = 'Please fill in your email';
            }
            if(!isset($post['password']) || $post['password'] === '') {
                $errors['password'] = 'Please fill in your password';
            }
            elseif(strlen($post['password']) < 6) {
                $errors['password'] = 'PAssword must be 6 caracters minimum';
            }
            if(!isset($post['password_confirm']) || $post['password_confirm'] !== $post['password']) {
                $errors['password_confirm'] = 'Passwords are differents';
            }

            // Check email exists
            if(!$errors && User::find(['email' => $post['email']])->found()) {
                $errors[] = 'This email already exists, please choose another or login';
            }

            if($errors) {
                old($post);
                flash()->error($errors);
                redirect(route('register'));
            }

            $User = new User;
            $User->name = $post['name'];
            $User->email = $post['email'];
            $User->password = make_hash($post['password']);
            $User->save();

            flash()->success('You are registered, you can now login !');
            redirect(route('login'));
        }

        $this->view('auth.register');
    }
}