# Structure

- **[Overview](/structure?id=overview)**
    - [App directory](/structure?id=app-directory)
    - [Public directory](/structure?id=public-directory)
    - [Resources directory](/structure?id=resources-directory)
    - [Vendor directory](/structure?id=vendor-directory)
- **[The App directory](/structure?id=the-app-directory)**
    - [Controllers](/structure?id=controllers)
    - [Models](/structure?id=models)
    - [Views](/structure?id=views)


## Overview

### App directory

The `app` directory contains the core code of your application.
We'll explore this directory in more detail soon;
however, almost all of the files in your application will be in this directory.


### Public directory

The `public` directory contains the [index.php](https://github.com/rseon/mallow/blob/master/public/index.php) file,
which is the entry point for all requests entering your application and configures autoloading.
This directory also houses your assets such as images, JavaScript, and CSS.


### Resources directory

The `resources` directory contains all of your language files. You can set un-compiled assets such as SASS or JavaScript.


### Vendor directory

The `vendor` directory contains your [Composer](https://getcomposer.org/) dependencies,
like the [Mallow Core](https://github.com/rseon/mallow-core)



## The App directory

The majority of your application is housed in the `app` directory.
By default, this directory is namespaced under `App` and is autoloaded by [Composer](https://getcomposer.org/)
using the [PSR-4 autoloading standard](https://www.php-fig.org/psr/psr-4/).

You can find there two files :
- [config.php](https://github.com/rseon/mallow/blob/master/app/config.php) which is the configuration file
- [routes.php](https://github.com/rseon/mallow/blob/master/app/routes.php) which is the routes file


### Controllers

Almost all of the logic to handle requests entering your application will be placed in this directory.


### Models

Almost all of the logic to handle data returning by the database will be placed in this directory.


### Views

The layouts and the views will be placed in this directory.
