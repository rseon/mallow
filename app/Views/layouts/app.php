<!DOCTYPE html>
<html lang="<?php echo get_locale() ?>">

<head>
    <?php $this->partial('layouts.partials.head'); ?>
    <title><?php echo get_meta('title', __('Mallow')) ?></title>
</head>

<body>
<div class="container">
    <ul>
        <?php
        // Lang switcher
        foreach(config('locales') as $locale => $localeData) {
            if($locale === get_locale()) {
                continue;
            }
            echo '<li><a href="'.get_current_url_locale($locale).'">'.$localeData['name'].'</a></li>';
        }
        ?>
    </ul>

    <?php $this->partial('layouts.partials.flash'); ?>

    <ul>
        <li><a href="<?php echo route('index') ?>" class="<?php if(get_current_route('name') === 'index') echo 'text-success' ?>"><?php echo __('Home') ?></a></li>
        <li><a href="<?php echo route('login') ?>" class="<?php if(get_current_route('name') === 'login') echo 'text-success' ?>"><?php echo __('Login') ?></a></li>
        <li><a href="<?php echo route('register') ?>" class="<?php if(get_current_route('name') === 'register') echo 'text-success' ?>"><?php echo __('Register') ?></a></li>
        <li><a href="<?php echo route('account') ?>" class="<?php if(get_current_route('name') === 'account') echo 'text-success' ?>"><?php echo __('My account') ?></a></li>
        <li><a href="<?php echo route('closure', ['id' => 4, 'foo' => 'bar']) ?>" class="<?php if(get_current_route('name') === 'closure') echo 'text-success' ?>">Test closure</a></li>
    </ul>

    <?php echo $this->content(); ?>
</div>

<?php $this->partial('layouts.partials.foot'); ?>
</body>
</html>
