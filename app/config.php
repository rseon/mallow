<?php

return [

    /*
     * Available locales
     */
    'locales' => ['en', 'fr'],

    /*
     * Default locale
     */
    'locale' => 'en',

    /*
     * Database
     */
    'database' => [
        'host' => getenv('DB_HOST'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'dbname' => getenv('DB_USERNAME'),
        'port' => getenv('DB_PORT'),
        'charset' => 'utf8',
        'on_error' => Rseon\Mallow\Database::ON_ERROR_EXCEPTION,
    ],

    /*
     * Namespace of controllers
     */
    'controllers' => 'App\\Controllers',

    /*
     * Path to the lang files
     */
    'langs_path' => '/resources/langs',

    /*
     * Path to the lang files
     */
    'views_path' => '/app/Views',
];