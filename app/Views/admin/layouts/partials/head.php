<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="<?php echo get_csrf() ?>">
<title><?php echo get_meta('title', __('Administration')) ?></title>
<link rel="stylesheet" href="<?php echo url('/vendor/adminlte/vendor/bootstrap/css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?php echo url('/vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css') ?>">
<link rel="stylesheet" href="<?php echo url('/vendor/adminlte/css/AdminLTE.min.css') ?>">
<link rel="stylesheet" href="<?php echo url('/vendor/adminlte/css/skins/skin-black.min.css') ?>">
<link rel="stylesheet" href="<?php echo url('/vendor/adminlte/vendor/iCheck/square/blue.css') ?>">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<?php
// Debugbar
if (getenv('APP_DEBUG')) {
    echo registry('Debugbar')->getJavascriptRenderer()->renderHead();
}
?>