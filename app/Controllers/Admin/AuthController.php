<?php

namespace App\Controllers\Admin;

class AuthController extends AbstractAdminController
{

    /**
     * Sign in user
     *
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function login()
    {
        // Check login
        if(is_post()) {
            $this->csrf(admin_url('/auth/login'));

            $post = sanitize_array($_POST);
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

            $this->user->auth($post['username'], $post['password']);
            if(!$this->user->isAuth()) {
                $reason = $this->user->getReason();
                switch($reason) {
                    case 'not_found':
                        $errors[] = 'Utilisateur non trouvé';
                        break;
                    case 'bad_password':
                        $errors[] = 'Mot de passe incorrect';
                        break;
                }

                old($post);
                flash()->error($errors);
                redirect(admin_url('/auth/login'));
            }

            flash()->success('Logged with success !');
            redirect(admin_url('/index/index'));
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
        if($this->user->isAuth()) {
            $this->user->setAuth(false);
        }

        redirect(admin_url('/index/index'));
    }
}