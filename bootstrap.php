<?php

if(!defined('ROOT')) {
    throw new RuntimeException('Constant ROOT must be defined');
}

// Autoload
require ROOT . '/vendor/autoload.php';

// Load env
Rseon\Mallow\Dotenv::load(ROOT);

// Check APP_KEY
if(!getenv('APP_KEY')) {
    throw new RuntimeException('No application encryption key has been specified');
}

// Start session (share it with subdomains)
ini_set('session.cookie_domain', '.'.getenv('APP_DOMAIN'));
session_start();

// Configuration
$config = require_once ROOT.'/app/config.php';

// Helpers
require_once ROOT.'/src/helpers.php';
foreach (glob(ROOT.'/src/Helpers/*.php') as $file) {
    require_once $file;
}

// Save config
registry('Config', $config);

// Set current locale
set_locale();

// Add routes
require_once ROOT.'/app/routes.php';
registry('Router', \Rseon\Mallow\Router::getInstance());

// Connect to Database
registry('Database', Rseon\Mallow\Database::connect(config('database')));

// Add debugbar
$debugbar = new DebugBar\StandardDebugBar();
$pdo = new DebugBar\DataCollector\PDO\TraceablePDO(registry('Database')->getPDO());
$debugbar->addCollector(new DebugBar\DataCollector\PDO\PDOCollector($pdo));
$debugbar->addCollector(new Rseon\Mallow\DataCollector\ViewDataCollector());
$debugbar->addCollector(new Rseon\Mallow\DataCollector\RouteDataCollector());
$debugbar->addCollector(new Rseon\Mallow\DataCollector\LocaleDataCollector());
$debugbar->addCollector(new Rseon\Mallow\DataCollector\AuthDataCollector());
$debugbar['time']->startMeasure('App', 'Application');
registry('Debugbar', $debugbar);

// Dispatch current route to controller
registry('Router')->dispatch(config('controllers'));

// Clear sessions
flash()->clear();
unset($_SESSION['__old']);
