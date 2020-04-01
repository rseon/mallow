<!DOCTYPE html>
<html lang="<?php echo get_locale() ?>">

<head>
    <?php $this->partial('layouts.partials.head'); ?>
    <title><?php echo get_meta('title', __('My account')) ?></title>
</head>

<body>
<div class="container">
    <?php $this->partial('layouts.components.flash'); ?>

    <h1><?php echo __('Hello, :name !', ['name' => $this->user['name']]) ?></h1>
    <p>
        <a href="<?php echo route('index') ?>"><?php echo __('Home') ?></a>
        <a href="<?php echo route('logout') ?>"><?php echo __('Logout') ?></a>
    </p>

    <?php echo $this->content(); ?>
</div>
<?php $this->partial('layouts.partials.foot'); ?>
</body>
</html>
