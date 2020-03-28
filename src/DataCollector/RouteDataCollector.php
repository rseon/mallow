<?php

namespace Rseon\Mallow\DataCollector;

use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

class RouteDataCollector extends DataCollector implements Renderable
{

    protected $route;

    public function collect()
    {
        return [
            'current' => $this->route,
            //'list' => router()->getCached(),
        ];
    }

    public function getName()
    {
        return 'route';
    }

    public function set($route)
    {
        $callbackname = gettype($route['callback']) === 'string' ? $route['callback'] : 'closure';

        $callback = $callbackname.'(';
        if($route['request']) {
            $callback .= implode(', ', array_map(function($k, $v) {
                $data = '$'.$k;
                if(is_array($v) || is_object($v)) {
                    $export = var_export($v, true);
                    $export = str_replace("\n", '', $export);
                    $export = preg_replace('/\s+/', ' ', $export);
                    $export = str_replace('array ( ', 'array(', $export);
                    $export = str_replace([', )', ',)'], ')', $export);
                    $data .= ' = '.$export;
                }
                else {
                    $data .= ' = \''.$v.'\'';
                }
                return $data;
            }, array_keys($route['request']), array_values($route['request'])));
        }
        $callback .= ')';

        $this->route = [
            'Name : ' . $route['name'],
            'Path : ' . ($route['path'] ?? null),
            'Method : ' . ($route['method'] ?? null),
            'Callback : ' . $callback,
        ];
    }

    public function getWidgets()
    {
        $name = $this->getName();

        return array(
            "$name" => [
                'icon' => 'eye',
                'widget' => 'PhpDebugBar.Widgets.ListWidget',
                'map' => "$name.current",
                'default' => '[]',
            ],
        );
    }
}