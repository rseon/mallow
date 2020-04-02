# Views

- **[Introduction](/views?id=introduction)**
- **[Path convention](/views?id=path-convention)**
- **[Creating a view](/views?id=creating-a-view)**
- **[Variables](/views?id=variables)**
    - [Retrieving a variable](/views?id=retrieving-a-variable)
- **[Partials](/views?id=partials)**
- **[Layouts](/views?id=layouts)**
    - [Minimal example](/views?id=minimal-example)
- **[Useful methods](/views?id=useful-methods)**


## Introduction

Views contain the HTML served by your application and separate your controller / application logic from your presentation logic.
Views are stored in the `app/Views` directory.

!> There is no templating system, it's only pure-PHP. Be careful to **only** show variables, don't be silly ;)

?> **Tip** : the base path to the view files is defined in the [app/config.php](https://github.com/rseon/mallow/blob/master/app/config.php) file


## Path convention

Use the "dot" syntax (instead of slash) to find a view in the directory.
For example `view('account.index')` will find the file `app/Views/account/index.php`.


## Creating a view

There are many ways to create a view :
- Using the helper : `view(string $path, array $args = [])`
- From a controller : `$this->view(string $path, array $args = [])`
- Directly instanciate the class : `new View(string $path, array $args = [])`


## Variables

You can assign variable when creating a view with the `$args` parameter.

Otherwise, once the view is created, use the `$view->assign($args, $value = null)` method. `$args` can be an array, or
a string and set with `$value`:

A third way is to set directly the data to the view : `$view->foo = bar`

```php
// On creation
$view = view('index', ['foo' => 'bar']);

// With assign method
$view->assign('foo', 'bar');

// Assigning with array
$view->assign(['foo' => 'bar']);

// Direct setting
$view->foo = 'bar';
```


### Retrieving a variable

Inside a view you can get the variable using `$this->foo`.

!> The variables are not escaped so be careful when handle data incoming from users



## Partials

Partials are view included in another view. Instead of create a new view, you can use the method
`$this->partial($path, $args)` to insert another view in the current view.


## Layouts

Layouts are views that wrap all common HTML codes and includes the content (ie. view defined in the action of the
controller). The default layout is `app/Views/layouts/app.php` but can be changed with the controller method
`$this->layout()`. To include the content of the layout you must call the method `$this->content()`.

### Minimal example

A minimal layout could be :

```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<?php echo $this->content(); ?>
</body>
</html>
```


## Useful methods

Some methods inherits from `Rseon\Mallow\View` :

```php
// Assign a variable
$View->assign($key, $value = null) : Rseon\Mallow\View

// Get content of a layout
$View->content() : null

// Create a partial
$View->partial(string $path, array $args = []) : null

// Display a view
$View->render() : null
```