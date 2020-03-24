<!DOCTYPE html>
<html lang="<?php echo get_locale() ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo get_csrf() ?>">
    <?php $this->partial('layouts.partials.head'); ?>
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

    <?php
    $this->partial('layouts.partials.flash');
    ?>

    <ul>
        <li><a href="<?php echo route('index') ?>" class="<?php if(get_current_route('name') === 'index') echo 'text-success' ?>"><?php echo __('Home') ?></a></li>
        <li><a href="<?php echo route('index.test', ['id' => 4]) ?>" class="<?php if(get_current_route('name') === 'index.test') echo 'text-success' ?>"><?php echo __('Localized route') ?></a></li>
    </ul>
</div>

<?php
echo $this->content();

$this->partial('layouts.partials.foot');
?>
</body>
</html>
