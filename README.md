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

**Composer note** : the user and core files are in this repository so you can't install it via composer.
Howevere [Composer](https://getcomposer.org/) is required for autoloading and installing dependencies. 


### Automatic via bash script

Run these commands on your server :

```
curl -O https://gist.githubusercontent.com/rseon/3626492b32cf8c3290f2f868a94b94e3/raw/546214d561223d4d1616bda78b55b35fc624321c/mallow-installer.sh
chmod +x mallow-installer.sh
./mallow-installer.sh
```

Or if you don't trust [the bash file](https://gist.githubusercontent.com/rseon/3626492b32cf8c3290f2f868a94b94e3)
you can execute manually these commands on your server :

```
wget -O mallow.zip https://github.com/rseon/mallow/archive/master.zip
unzip mallow.zip
mv mallow-master/ mallow
rm mallow.zip
cd mallow
composer install
cp .env.example .env
php src/bin/keygen
```

### Manually

- [Download the project](https://github.com/rseon/mallow/archive/master.zip), upload the file on your server and unzip it
- Run `composer install`
- Copy `.env.example` to `.env`
- Generate your `APP_KEY` running `php src/bin/keygen` and paste the result in your `.env` file 


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
