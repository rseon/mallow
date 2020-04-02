<?php

namespace App\Controllers\Admin;

class AuthController extends AbstractAdminController
{

    /**
     * Redirect to dashboard if logged
     *
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function init()
    {
        parent::init();
        if(in_array($this->getAction(), ['login', 'lockscreen'])) {
            if($this->user && $this->user->isAuth()) {
                redirect(admin_url('/'));
            }
        }
    }

    /**
     * Sign in user
     *
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function login()
    {
        if($this->remember) {
            $this->logout();
        }

        // Check login
        if(is_post()) {
            $this->csrf(admin_url('/auth/login'));

            $post = $this->request();
            $errors = [];
            if(!isset($post['username']) || $post['username'] === '') {
                $errors['username'] = 'Merci de renseigner votre identifiant';
            }
            if(!isset($post['password']) || $post['password'] === '') {
                $errors['password'] = 'Merci de renseigner votre mot de passe';
            }

            if($errors) {
                old($post);
                flash()->error($errors);
                redirect(admin_url('/auth/login'));
            }

            $this->authUser($this->user, [
                'username' => $post['username'],
                'password' => $post['password'],
                'remember' => $this->request('remember', false) === "1",
            ], admin_url('/auth/login'));

            $redirect = admin_url('/index/index');
            if(isset($_SESSION['back_login']) && $_SESSION['back_login']) {
                $redirect = $_SESSION['back_login'];
                unset($_SESSION['back_login']);
            }
            redirect($redirect);
        }

        $this->layout('layouts.auth');
        $this->view('auth.login');
    }

    /**
     * Sign out user
     *
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function logout()
    {
        $this->user->setAuth(false);
        redirect(admin_url('/auth/login'));
    }

    /**
     * Lockscreen page
     *
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function lockscreen()
    {
        $user = $this->remember;
        if(!$user) {
            redirect(admin_url('/auth/login'));
        }

        if(is_post()) {
            $this->csrf(admin_url('/auth/lockscreen'));

            $post = $this->request();
            $errors = [];
            if (!isset($post['password']) || $post['password'] === '') {
                $errors['password'] = 'Merci de renseigner votre mot de passe';
            }

            if ($errors) {
                flash()->error($errors);
                redirect(admin_url('/auth/lockscreen'));
            }

            $this->authUser($user, [
                'username' => $user->email,
                'password' => $post['password'],
                'remember' => true,
            ], admin_url('/auth/lockscreen'));

            $redirect = admin_url('/index/index');
            if(isset($_SESSION['back_login']) && $_SESSION['back_login']) {
                $redirect = $_SESSION['back_login'];
                unset($_SESSION['back_login']);
            }
            redirect($redirect);
        }

        $this->layout('layouts.auth');
        $this->view('auth.lockscreen', [
            'user' => $user->getAttributes(),
        ]);
    }

    /**
     * Authenticate the user
     *
     * @param $user
     * @param $data
     * @param $redirect_error
     */
    protected function authUser($user, $data, $redirect_error)
    {
        $errors = [];
        $user->auth($data['username'], $data['password'], $data['remember']);
        if(!$user->isAuth()) {
            $reason = $user->getReason();
            switch($reason) {
                case 'not_found':
                    $errors[] = 'Utilisateur non trouvé';
                    break;
                case 'bad_password':
                    $errors[] = 'Mot de passe incorrect';
                    break;
            }

            unset($data['password']);
            old($data);
            flash()->error($errors);
            redirect($redirect_error);
        }
    }
}