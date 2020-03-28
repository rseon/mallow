# Router

- **[Creating routes](/router?id=creating-routes)**
- **[Available methods](/router?id=available-methods)**
    - [Definition](/router?id=definition)
- **[Regex](/router?id=regex)**
- **[Callback](/router?id=callback)**
    - [As a string](/router?id=as-a-string)
    - [As a closure](/router?id=as-a-closure)
- **[Working example](/router?id=working-example)**
- **[Useful methods](/router?id=useful-methods)**


## Creating routes

The routes must be defined in the [app/routes.php](https://github.com/rseon/mallow/blob/master/app/routes.php) file.
You are free to organize it as you want but keep in mind the file must create routes with the `Rseon\Mallow\Router`
and must return this instance.

All the routes defined will be processed in the `public` directory by the
[index.php](https://github.com/rseon/mallow/blob/master/public/index.php) (according to the
[.htaccess](https://github.com/rseon/mallow/blob/master/public/.htaccess) file). That means when you visit an URL, the
router will get it and returns the callback defined by the route.


## Available methods

The following methods are handled as HTTP methods.

```php
$Router->get(string $name, string $path, string|closure $callback, array $request = []);
$Router->post(string $name, string $path, string|closure $callback, array $request = []);
$Router->map(array $methods, string $name, string $path, string|closure $callback, array $request = []);
```

?> If a route is defined with `get`, only GET request will match this route (not POST).

?> **Tip** : To allow multiple HTTP methods, you can use the `map` method, setting the parameter `$methods` with array of allowed HTTP methods.


### Definition

- `$name` : you could request a route with it using the `route()` helper.<br>
The name must be unique by method (get or post).
- `$path` : the uri which match the route
- `$callback` : can be a string (controller and action to process) or directly a closure
- `$request` : required if `$path` is a regex, it will be the name of each part
- `$methods` : only for the `$map` method to set the allowed HTTP methods 


## Regex

The `$path` can be a regex (regular expression) to allow you to match dynamic paths.
You must define in `$request` the parameter names to map them.

Example : if the path is `/(.*)/(.*)-([0-9]+).html`, the URL `/my/super-route-123.html` will match but
not `/my-route.html`.

!> Be careful with the order of your routes when you have regex, the first found which match will be handled.

```php
$Router->get($name, '/(.*)/(.*)-([0-9]+).html', $callback, ['country', 'slug', 'id']);
```
In this example, there are 3 parameters mapped :
- First `(.*)` is the country,
- Second `(.*)` is the slug,
- `([0-9]+)` is the id,


## Callback

The callback can be a *string* or a *closure*.

In the both case, the parameters of the route will be injected as arguments of the method / function.
If the route is a regex, the first arguments are the parameters.

In all case, the last is the request (GET and/or POST) as array.


### As a string

You must define it as `YourController@yourAction` to call the method `yourAction` in the
`app/Controllers/YourController.php` file.


### As a closure

Its a function that take the same arguments as the controller action.
It could be useful when process request without the utilities of the controller.

Otherwise, you can use a specific controller to use callback like the controllers.

Example : 
```php
$Router->get('closure', '/closure-([0-9]+).html', function(int $id, array $request = []) {
    $controller = new Rseon\Mallow\Controllers\ClosureController();
    $controller->view('index', [
        'id' => $id,
        'request' => $request,
    ]);
    $controller->run();
}, ['id']);
``` 


## Working example

- `app/routes.php`
```php
$Router->get('dynamic.test', '/(.*)/(.*)-([0-9]+).html', 'IndexController@listCountry', ['country', 'slug', 'id']);
```

- Anywhere to call the route
```php
$route = route('dynamic.test', [
    'country' => 'france',
    'slug' => 'ma-boutique',
    'id' => 22,
    'sort' => 'asc',
]);
```

- URL generated : `/france/ma-boutique-22.html?sort=asc`

- `app/Controllers/IndexController.php`
```php
// ...
public function listCountry(string $country, string $slug, int $id, array $request = []) {
    // $country === string 'france'
    // $slug === string 'ma-boutique'
    // $id === int 22
    // $request === array('sort' => 'asc')
}
// ...
```


## Useful methods

Thanks to the helper `router()` you can retrieve the main `Rseon\Mallow\Router` and accessing its methods :

```php
// Add new route
$Router->add(string $method, string $name, string $path, $callback, array $request = []) : Rseon\Mallow\Router

// Add new route as an array
$Router->addArray(array $data) : Rseon\Mallow\Router

// New GET route
$Router->get(string $name, string $path, $callback, array $request = []) : Rseon\Mallow\Router

// Returns the current route from URL
$Router->getCurrentRoute(string $current_url = null) : array

// Returns a route by its name
$Router->getRoute(string $name, string $method = null) : array

// Returns the routes
$Router->getRoutes(string $method = null) : array

// Get the url of a route based on its parameters
$Router->getUrl(array $route, $params = []) : string

// New GET/POST route
$Router->map(array $methods, string $name, string $path, $callback, array $request = []) : Rseon\Mallow\Router

// New POST route
$Router->post(string $name, string $path, $callback, array $request = []) : Rseon\Mallow\Router

// Performs a redirection
$Router->redirect(string $url) : void

// Remove a route
$Router->remove(string $method, string $name) : Rseon\Mallow\Router

// Get the route URL by name and parameters
$Router->routeTo(string $name, array $params = [], string $method = null) : string
```
