# Mallow - Another PHP framework

Based on the [Mallow Core](https://github.com/rseon/mallow-core).

Features included :
- Localization : EN (default) and FR
- Authentication and account part
- Some tests


## Installation

### Automatic via bash script

[Check the code](https://gist.github.com/rseon/3626492b32cf8c3290f2f868a94b94e3)
```
curl -O https://gist.githubusercontent.com/rseon/3626492b32cf8c3290f2f868a94b94e3/raw/f5b4f02ebdbc0775f4c4177e7f553539ffd5f830/mallow-installer.sh
chmod +x mallow-installer.sh
./mallow-installer.sh
```

Or you can do it manually :
```
wget -O mallow.zip https://github.com/rseon/mallow/archive/master.zip
unzip mallow.zip
mv mallow-master/ mallow
rm mallow.zip
cd mallow
composer install
cp .env.example .env
chown -R www-data:www-data .
php vendor/rseon/mallow-core/src/bin/keygen
```

### Download it

- Download this repository as zip or tar.gz
- Upload and unzip/untar it on your server
- Generate your `APP_KEY` running `php vendor/rseon/mallow-core/src/bin/keygen` and paste it into the `.env` file

**Note** : If the `.env` file was not created when installing this package, rename `.env.example` to `.env`


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
- `/.env` : sensitive data (don't commit them !)
