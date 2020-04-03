<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="<?php echo get_csrf() ?>">
<?php
// Debugbar
echo debug()->renderHead();

// Alternate
foreach(array_keys(config('locales')) as $locale) {
    if($locale !== get_locale()) {
        echo '<link rel="alternate" hreflang="'.$locale.'" href="'.no_query_string(get_current_url_locale($locale), ['page']).'">'.PHP_EOL;
    }
}
?>
<link rel="canonical" href="<?php echo no_query_string(get_current_url(), ['page']) ?>">
<link rel="stylesheet" href="<?php echo asset('/css/vendor.css') ?>">
<link rel="stylesheet" href="<?php echo asset('/css/app.css') ?>">
