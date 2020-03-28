# Controllers

- **[Introduction](/controllers?id=introduction)**
- **[Defining controllers](/controllers?id=defining-controllers)**
- **[Single Action Controllers](/controllers?id=single-action-controllers)**
- **[Useful methods](/controllers?id=useful-methods)**


## Introduction

Instead of defining all of your request handling logic as Closures in route files, you may wish to organize this
behavior using Controller classes.
Controllers can group related request handling logic into a single class.
Controllers are stored in the `app/Controllers` directory.


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
$Router->get('user.profile', '/user/([0-9]+)', 'UserController@show', ['id']);
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
$Router->get('user.profile', '/user/([0-9]+)', 'UserController', ['id']);
```


## Useful methods

Some methods inherits from `Rseon\Mallow\Controller` and can be used in the controllers :

```php
// CSRF protection
$this->csrf(string $redirect, &$data = null) : void

// Disable the layout
$this->disableLayout() : void

// Returns the current action
$this->getAction() : string

// Returns the layout
$this->getLayout() : Rseon\Mallow\View

// Returns the raw POST
$this->getPost() : array

// Returns the view
$this->getView() : Rseon\Mallow\View

// Get if request method is POST
$this->isPost() : bool

// Make a JSON response
$this->json($data = null) : bool

// Define the layout to use
$this->layout(string $layout, array $args = []) : Rseon\Mallow\View

// Set the page title
$this->setTitle(string $title) : void

// Define the view to use
$this->view(string $layout, array $args = []) : Rseon\Mallow\View
```
