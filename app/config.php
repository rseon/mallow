<?php

return [

    /*
     * Available locales.
     *
     * The locale is defined by subdomain (ie 'fr.my-domain.dv').
     * You can omit it for the default locale.
     */
    'locales' => [
        'en' => [
            'name' => 'English',
        ],
        'fr' => [
            'name' => 'Français',
            'subdomain' => 'fr',
        ],
    ],

    /*
     * Default locale
     */
    'locale' => 'en',

    /*
     * Database credentials and config
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
     * Namespace of models
     */
    'models' => 'App\\Models',

    /*
     * Path to the view files
     */
    'views_path' => '/app/Views',

    /*
     * Path to the lang files
     */
    'langs_path' => '/resources/langs',

    'path_admin' => getenv('PATH_ADMIN'),
];