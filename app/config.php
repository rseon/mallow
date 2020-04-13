<?php
/*
 * This is your local configuration.
 *
 * You are free to add your own keys or override the base configuration.
 * Because app config and base config are merged, you can override all or only part you want.
 */
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

];