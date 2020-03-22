<!DOCTYPE html>
<html lang="<?php echo get_locale() ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php include_view('layouts.partials.head'); ?>
</head>

<body>

<?php
include_view('layouts.partials.flash');
?>

<ul>
    <li><a href="<?php echo route('index') ?>"><?php echo __('Home') ?></a></li>
</ul>

<?php
// Includes view
$view = registry('Controller')->getView();
if($view) {
    include_view($view['path'], $view['args']);
}

include_view('layouts.partials.foot');
?>
</body>
</html>
