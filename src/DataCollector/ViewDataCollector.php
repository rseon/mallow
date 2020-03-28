<?php

namespace Rseon\Mallow\DataCollector;

use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

class ViewDataCollector extends DataCollector implements Renderable
{

    protected $views = [];

    public function collect()
    {
        $views = $this->getViews();
        return [
            'count' => count($views),
            'views' => $views,
        ];
    }

    public function getViews()
    {
        return $this->views;
    }

    public function getName()
    {
        return 'views';
    }

    public function addView($view)
    {
        $data = $view['path'];

        if($view['args']) {
            $data .= ' ['.implode(', ', array_map(function($k, $v) {
                $type = gettype($v);
                if($type === 'object') {
                    $type = get_class($v);
                }
                return $type . ' $'.$k;
            }, array_keys($view['args']), array_values($view['args']))).']';
        }

        $this->views[] = $data;
    }

    public function getWidgets()
    {
        $name = $this->getName();

        return array(
            "$name" => [
                'icon' => 'eye',
                'widget' => 'PhpDebugBar.Widgets.ListWidget',
                'map' => "$name.views",
                'default' => '[]',
            ],
            "$name:badge" => [
                "map" => "$name.count",
                "default" => "null"
            ],
        );
    }
}