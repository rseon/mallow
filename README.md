# Mallow - Another PHP framework

This is the minimal installation to use [Mallow](https://github.com/rseon/mallow-core).

Features included :
- Localization : EN (default) and FR
- Authentication and account part
- Some tests


## Installation

- `composer require rseon/mallow`
- Generate your `APP_KEY` running `php vendor/rseon/mallow-core/src/bin/keygen` and past it into the `.env` file

**Note** : If the `.env` file was not created when installing this package, rename `.env.example` to `.env`


### Database

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

The following files are required to make the framework work. The others can be deleted.

- `/app/Controller/IndexController.php` : your homepage (called by the `index` route)
- `/app/Views/layouts/app.php` : main layout
- `/app/Views/index.php` : homepage
- `/app/config.php` : app configuration
- `/app/routes.php` : app configuration
- `/resources/langs` : folder required but files into it are optional
- `/.env` : sensitive data (don't commit them !)
