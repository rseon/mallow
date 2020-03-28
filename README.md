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
- [Debugbar](https://github.com/maximebf/php-debugbar)


## Installation

Via [Composer](https://getcomposer.org/) : `composer require rseon/mallow`

After installation, generate your `APP_KEY` and paste it into the `.env` file running :

`php src/bin/keygen` 


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
