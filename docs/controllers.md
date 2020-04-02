# Controllers

- **[Introduction](/controllers?id=introduction)**
- **[Namespacing](/controllers?id=namespacing)**
- **[Defining controllers](/controllers?id=defining-controllers)**
- **[Single Action Controllers](/controllers?id=single-action-controllers)**
- **[Specific controllers](/controllers?id=specific-controllers)**
    - [ErrorController](/controllers?id=errorcontroller)
    - [ClosureController](/controllers?id=closurecontroller)
- **[Useful methods](/controllers?id=useful-methods)**


## Introduction

Instead of defining all of your request handling logic as Closures in route files, you may wish to organize this
behavior using Controller classes.
Controllers can group related request handling logic into a single class.
Controllers are stored in the `app/Controllers` directory.


## Namespacing

Controllers are autoloaded thanks to their namespace.
That means you can put controllers in subdirectories to organize them as *modules*.

For example, if you want separate account controllers from front-office you may want to put your controllers in
`app/Controllers/Account`. So an `IndexController` will have the namespace `App\Controllers\Account` and you can use
it as callback in your route using `Account\IndexController`.
 
?> **Tip** : the base namespace is defined in the [app/config.php](https://github.com/rseon/mallow/blob/master/app/config.php) file



## Defining controllers

```php
<?php

namespace App\Controllers;

use App\Models\User;
use Rseon\Mallow\Controller;

class UserController extends Controller
{
    /**
     * Show the profile for the given user
     *
     * @param int $id
     * @param array $request
     */
    public function show(int $id, array $request)
    {
        $this->view('user.profile', ['user' => User::findOrFail($id)]);
    }
}
```

You can define a route to this controller action like so :

```php
Router::get('user.profile', '/user/([0-9]+)', 'UserController@show', ['id']);
```

Now, when a request matches the specified route URI, the `show` method on the `UserController` class will be executed.
The route parameters will also be passed to the method.


## Single Action Controllers

If you would like to define a controller that only handles a single action, you may place a single `__invoke`
method on the controller :

```php
<?php

namespace App\Controllers;

use App\Models\User;
use Rseon\Mallow\Controller;

class UserController extends Controller
{
    /**
     * Show the profile for the given user
     *
     * @param int $id
     * @param array $request
     */
    public function __invoke(int $id, array $request)
    {
        $this->view('user.profile', ['user' => User::findOrFail($id)]);
    }
}
```

When registering routes for single action controllers, you do not need to specify a method :

```php
Router::get('user.profile', '/user/([0-9]+)', 'UserController', ['id']);
```

## Specific controllers

There are two specific controllers you can extend in your app.


### ErrorController

This controller (and more specifically the `routeNotFound` method) is called when you try to access an inexistant route.
The default controller launch an exception with a 404 header, but you can find an override on
[app/Controllers/ErrorController.php](https://github.com/rseon/mallow/blob/master/app/Controllers/ErrorController.php)
which display a view.


### ClosureController

This controller is useful when you want to use a closure in your route, keeping advantages of controllers :

```php
Router::get('test.closure', '/closure-([0-9]+).html', function(int $id, array $request = []) {
    $controller = new Rseon\Mallow\Controllers\ClosureController();
    $controller->view('index', [
        'name' => 'from Closure :)',
        'id' => $id,
        'request' => $request,
    ]);
    $controller->run();
}, ['id']);
```


## Useful methods

Some methods inherits from `Rseon\Mallow\Controller` and can be used in the controllers :

```php
// CSRF protection
$this->csrf(string $redirect, &$data = null) : void

// Disable the layout
$this->disableLayout() : void

// Disable the view (and the layout)
$this->disableView() : void

// Returns the current action
$this->getAction() : string

// Returns the layout
$this->getLayout() : Rseon\Mallow\View

// Returns the view
$this->getView() : Rseon\Mallow\View

// Define the layout to use
$this->layout(string $layout, array $args = []) : Rseon\Mallow\View

// Get the current request (GET or POST)
$this->request(string $key = null, $default = null) : mixed

// Set the page title
$this->setTitle(string $title) : void

// Define the view to use
$this->view(string $layout, array $args = []) : Rseon\Mallow\View
```
