<?php

namespace App\Traits\Admin;

trait AdminUtils
{
    /**
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
                'url' => admin_url('/'),
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

    public function setHeader(string $header, string $subheader = null)
    {
        $this->layout->assign('layout_header', $header);
        $this->layout->assign('layout_subheader', $subheader);
    }
}