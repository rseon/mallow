# Configuration

- **[Introduction](/config?id=introduction)**
- **[Environment configuration](/config?id=environment-configuration)**
    - [Variables](/config?id=variables)
    - [Retrieve variables](/config?id=retrieve-variables)


## Introduction 

The [src/default_config.php](https://github.com/rseon/mallow/blob/master/src/default_config.php) file contains the base
configuration. You can override it using your own [app/config.php](https://github.com/rseon/mallow/blob/master/app/config.php) file.

These files are documented so feel free to look through the files and get familiar with the options available to you.

?> **Tip** : Because app config and base config are merged, you can override all or only part you want.

```php
// in src/default_config.php
'foo' => 'bar',
'baz' => [
    'qux' => 'quux',
    'corge' => 'grault',
],

// in app/config.php
'foo' => 'garply',
'baz' => [
    'qux' => 'waldo',
],

// Result will be
'foo' => 'garply',
'baz' => [
    'qux' => 'waldo',
    'corge' => 'grault',
],
```


## Environment configuration

It is often helpful to have different configuration values based on the environment where the application is running.
For example, you may wish to use a different database connection locally than you do on your production server.

In a fresh Mallow installation, the root directory of your application will contain a `.env.example` file.
This file will automatically be renamed to `.env`. Otherwise, you should rename the file manually.

Your `.env` file should not be committed to your application's source control, since each developer / server
using your application could require a different environment configuration.
Furthermore, this would be a security risk in the event an intruder gains access to your source control repository,
since any sensitive credentials would get exposed.

?> Any variable in your `.env` file can be overridden by external environment variables such as server-level or system-level environment variables.

The functional is inspired of the [Dotenv](https://github.com/vlucas/phpdotenv) PHP library.


### Variables

| Name | Description |
| ---------- | ------------------------------ |
| **APP_ENV** | This is your app environment |
| **APP_DEBUG** | If true, shows the Debugbar |
| **APP_KEY** | The secure app key |
| **APP_DOMAIN** | Your website domain |
| **APP_HTTPS** | True if you use HTTPS |
| **DB_HOST** | Database host |
| **DB_PORT** | Database port |
| **DB_DATABASE** | Database name |
| **DB_USERNAME** | Database username |
| **DB_PASSWORD** | Database password |


### Retrieve variables

To retrieve an environment variable you can use the `getenv` function.
