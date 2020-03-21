<?php
define('ROOT_FRAMEWORK', realpath(dirname(__FILE__).'/../'));

if ($_SERVER['REQUEST_URI'] === '/index.php') {
    header('Location: /');
    exit;
}

require_once(ROOT_FRAMEWORK.'/vendor/rseon/mallow-core/bootstrap.php');
