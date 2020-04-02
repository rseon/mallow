<?php

namespace App\Controllers\Admin;

use App\Models\Admin\User;
use Rseon\Mallow\Exceptions\ModelException;

class UserController extends AbstractAdminController
{

    public function init()
    {
        parent::init();
        $this->setActiveMenu('user');
    }

    /**
     * List of resources
     */
    public function index()
    {
        $list = (new User)->getAll();

        $this->breadcrumbs(['text' => 'Utilisateurs']);
        $this->setHeaderText('Utilisateurs', count($list));
        $this->setTitle('Utilisateurs');
        $this->view('user.index', compact('list'));
    }

    /**
     * Creation form
     */
    public function create()
    {
        $this->breadcrumbs([
            ['link' => admin_url('/user'), 'text' => 'Utilisateurs'],
            ['text' => 'Nouvel utilisateur'],
        ]);
        $this->setHeaderText('Nouvel utilisateur');
        $this->setTitle('Nouvel utilisateur');
        $this->view('user.form', [
            'action' => admin_url('/user/store'),
        ]);
    }

    /**
     * Create the resource
     */
    public function store()
    {
        $redirect = admin_url('/user/create');
        $this->csrf($redirect);

        $user = new User();
        $this->validateModel($user, $redirect);

        $user->setAttributes([
            'name' => $this->request('name'),
            'email' => $this->request('email'),
            'password' => make_password('azertyuiop'),
        ]);

        $this->saveModel($user, $redirect);

        flash()->success('Utilisateur enregistré');
        redirect(admin_url('/user'));
    }

    /**
     * Edition form
     */
    public function edit()
    {
        $user = $this->findUser();

        $this->breadcrumbs([
            ['link' => admin_url('/user'), 'text' => 'Utilisateurs'],
            ['text' => 'Modification utilisateur'],
        ]);
        $this->setHeaderText('Modification utilisateur');
        $this->setTitle('Modification utilisateur');
        $this->view('user.form', [
            'action' => admin_url('/user/update', ['id' => $user->id]),
            'user' => $user,
        ]);
    }

    /**
     * Update the resource
     */
    public function update()
    {
        $user = $this->findUser();
        $redirect = admin_url('/user/edit', ['id' => $user->id]);
        $this->csrf($redirect);
        $this->validateModel($user, $redirect);

        $user->setAttributes([
            'name' => $this->request('name'),
        ]);

        $this->saveModel($user, $redirect);

        flash()->success('Utilisateur mis à jour');
        redirect($redirect);
    }

    /**
     * Delete the resource
     */
    public function delete()
    {
        $redirect = admin_url('/user');
        $user = $this->findUser();

        if($user->id === $this->user->getAuth()['id']) {
            flash()->error('Vous ne pouvez pas supprimer votre propre compte');
            redirect($redirect);
        }

        $user->delete();

        flash()->success('Utilisateur supprimé');
        redirect($redirect);
    }

    /**
     * Find the resource in the request
     *
     * @param array $request
     * @return User
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    protected function findUser()
    {
        return $this->findModel(User::class, 'Utilisateur inexistant', admin_url('/user'));
    }
}