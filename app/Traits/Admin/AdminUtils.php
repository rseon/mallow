<?php

namespace App\Traits\Admin;

use Rseon\Mallow\Exceptions\ModelException;

trait AdminUtils
{
    /**
     * Set the breadcrumbs
     *
     * @param array $paths
     * @param bool $show_index
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function breadcrumbs(array $paths = [], bool $show_index = true)
    {
        $links = [];

        if($show_index) {
            $links[] = [
                'text' => 'Tableau de bord',
                'link' => admin_url('/'),
                'icon' => 'tachometer',
            ];
        }

        if($paths) {
            if(!isset($paths[0])) {
                $paths = [$paths];
            }

            foreach($paths as $p) {
                $links[] = $p;
            }
        }

        $this->layout->assign('layout_breadcrumbs', $links);
    }

    /**
     * Set page header text
     *
     * @param string $header
     * @param string|null $subheader
     */
    public function setHeaderText(string $header, string $subheader = null)
    {
        $this->layout->assign('layout_header', $header);
        $this->layout->assign('layout_subheader', $subheader);
    }

    /**
     * Set active menu in the sidebar
     *
     * @param string $menu
     */
    public function setActiveMenu(string $menu)
    {
        $this->layout->assign('layout_active_menu', $menu);
    }

    /**
     * Find a model by the request
     *
     * @param $model
     * @param string $message
     * @param null $redirectError
     * @param string $key
     * @return mixed
     * @throws \Rseon\Mallow\Exceptions\AppException
     */
    public function findModel($model, $message = 'Entité inexistante', $redirectError = null, $key = 'id')
    {
        $id = (int) $this->request($key, 0);
        $instance = $model::find($id);
        if(!$id || !$instance->found()) {
            flash()->error($message);
            if(!$redirectError) {
                $redirectError = admin_url('/index');
            }
            redirect($redirectError);
        }

        return $instance;
    }

    /**
     * Validate the model with the request and redirect if error
     *
     * @param $model
     * @param $redirectError
     */
    public function validateModel($model, $redirectError)
    {
        if($errors = $model->validate($this->request())) {
            old($this->request());
            flash()->error($errors);
            redirect($redirectError);
        }
    }

    /**
     * Save the model and redirect if error
     *
     * @param $model
     * @param $redirectError
     */
    public function saveModel($model, $redirectError)
    {
        try {
            $model->save();
        } catch(ModelException $e) {
            old($this->request());
            flash()->error($e->getMessage());
            redirect($redirectError);
        }
    }
}