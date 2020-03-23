<?php

namespace App\Controllers;

use Rseon\Mallow\Controller;

class IndexController extends Controller
{
    /**
     * Index page
     *
     * @param array $request
     * @return $this
     */
    public function index(array $request)
    {
        $this->setTitle("Mallow - Another PHP Framework");

        $name = 'Mallow';

        $this->view('index', compact('name'));
        return $this;
    }

    /**
     * Test page
     *
     * @param int $id
     * @param array $request
     * @return $this
     */
    public function test(int $id, array $request)
    {
        $this->view('test', compact('id'));
        return $this;
    }

    /**
     * Test form
     *
     * @param array $request
     * @return $this
     */
    public function testform(array $request)
    {
        if($this->isPost()) {
            $this->csrf(route('index'));
            $post = sanitize_array($this->getPost());

            $errors = [];
            if(!isset($post['name']) || $post['name'] === '') {
                $errors['name'] = 'Please fill name';
            }
            if(!isset($post['email']) || $post['email'] === '') {
                $errors['email'] = 'Please fill email';
            }

            if($errors) {
                old($post);
                flash()->error($errors);
                redirect(route('index'));
            }

            flash()->success('Good job :)');
            redirect(route('index'));
        }
    }
}