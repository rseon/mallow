<!DOCTYPE html>
<html lang="<?php echo get_locale() ?>">

<head>
    <?php $this->partial('layouts.partials.head'); ?>
    <title><?php echo get_meta('title', __('Administration')) ?></title>
</head>

<body>
<div class="container">
    <?php $this->partial('layouts.partials.flash'); ?>

    <?php echo $this->content(); ?>
</div>
<?php $this->partial('layouts.partials.foot'); ?>
</body>
</html>
