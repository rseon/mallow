<?php

namespace Rseon\Mallow\DataCollector;

use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

class LocaleDataCollector extends DataCollector implements Renderable
{

    public function collect()
    {
        return get_locale();
    }

    public function getName()
    {
        return 'locale';
    }

    public function getWidgets()
    {
        return array(
            "locale" => array(
                "icon" => "language",
                "tooltip" => "Current locale",
                "map" => "locale",
                "default" => "''"
            )
        );
    }
}