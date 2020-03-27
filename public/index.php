<?php

/*
 * This constant must be defined on your root path
 */
define('ROOT_FRAMEWORK', realpath(dirname(__FILE__).'/../'));

/*
 * Only to redirect "/index.php" to "/"
 */
if ($_SERVER['REQUEST_URI'] === '/index.php') {
    header('Location: /');
    exit;
}

/*
 * Call the Mallow Core
 */
require_once(ROOT_FRAMEWORK.'/vendor/rseon/mallow-core/bootstrap.php');
