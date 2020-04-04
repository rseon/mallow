# Mallow - Another PHP framework

> A mix between old-school and standardized framework.

## Features

- MVC pattern
- Localization (by section / with multiple files)
- Router with localized routes and regex (be careful to sort them correctly !)
- Database abstraction layer with PDO
- Mini ORM (models are objects)
- Authentication
- Flash messages and input sessions
- Registry to share data
- Dotenv file for sensitive configuration (to not commit !)
- CSRF protection
- Console
- [Debugbar](https://github.com/maximebf/php-debugbar)


## Installation

Mallow utilizes [Composer](https://getcomposer.org/) to manage its dependencies.
So, before using this framework, make sure you have Composer installed on your machine.

- Run `composer create-project rseon/mallow my-project`
- Copy `.env.example` to `.env` (if `.env` file was not created)
- Generate your `APP_KEY` running `php console keygen` and paste the result in your `.env` file 

### Front-end

To manage assets in `/resources/assets` Mallow uses the awesome [Laravel Mix](https://laravel-mix.com/) which is
an excellent Webpack wrapper.

After installation run `npm install && npm run dev`

The framework includes [jQuery](https://jquery.com/), [Bootstrap](https://getbootstrap.com/) and
[FontAwesome](https://fontawesome.com/).


## Database

To test the User model, you can create the `users` table in your database :

```sql
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```


## Minimal installation

The following files are required to make the framework work.<br>
The others are here for testing purpose and can be deleted.

- `/app/Controller/IndexController.php` : your homepage (called by the `index` route)
- `/app/Models` : folder required but files into it are optional
- `/app/Views/layouts/app.php` : main layout
- `/app/Views/index.php` : homepage
- `/app/config.php` : app configuration
- `/app/routes.php` : routes
- `/resources/langs` : folder required but files into it are optional
- `/src` : the core files
- `/.env` : sensitive data (don't commit them !)
