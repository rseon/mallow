<?php

namespace Rseon\Mallow\DataCollector;

use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

class AuthDataCollector extends DataCollector implements Renderable
{

    public function collect()
    {
        $data = 'Not logged';
        if(is_array($_SESSION)) {
            $length = strlen('auth_');
            foreach($_SESSION as $name => $session) {
                if($session && substr($name, '0', $length) === 'auth_') {
                    if(isset($session['name'])) {
                        $data = $session['name'];
                    }
                    else {
                        $data = substr($name, -1*$length);
                    }
                }
            }
        }
        return $data;
    }

    public function getName()
    {
        return 'auth';
    }

    public function getWidgets()
    {
        return array(
            "auth" => array(
                "icon" => "user",
                "tooltip" => "Auth",
                "map" => "auth",
                "default" => "''"
            )
        );
    }
}