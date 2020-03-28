# Installation

- **[Server requirements](/install?id=server-requirements)**
- **[Installing the framework](/install?id=installing-the-framework)**
- **[Public directory](/install?id=public-directory)**
- **[Application key](/install?id=application-key)**
    - [Generate application key](/install?id=generate-application-key)


## Server requirements

- PHP >= 7.1
- JSON PHP Extension
- PDO PHP Extension


## Installing the framework

Mallow utilizes [Composer](https://getcomposer.org/) to manage its dependencies.
So, before using Mallow, make sure you have Composer installed on your machine.

Then simply run<br>
`composer require rseon/mallow`


## Public directory

After installing Mallow, you should configure your web server's document / web root to be the `public` directory.
The [index.php](https://github.com/rseon/mallow/blob/master/public/index.php) in this directory serves as the front controller for all HTTP requests entering your application

The [.htaccess](https://github.com/rseon/mallow/blob/master/public/.htaccess) file permit to Apache to serve
pretty URLs.


## Application Key
Your application key is a random string. The key can be set in the `.env` environment file.
If you have not renamed the `.env.example` file to `.env`, you should do that now.

!> If the application key is not set, your user sessions and other encrypted data will not be secure !


### Generate application key

To generate the key you can run the following command : <br>
`php vendor/rseon/mallow-core/src/bin/keygen`

Paste the result in `APP_KEY` of the `.env` file.
