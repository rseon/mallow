<?php
if (getenv('APP_DEBUG')) {
    echo registry('Debugbar')->getJavascriptRenderer()->renderHead();
}

foreach(array_keys(config('locales')) as $locale) {
    if($locale !== get_locale()) {
        echo '<link rel="alternate" hreflang="'.$locale.'" href="'.no_query_string(get_current_url_locale($locale), ['page']).'">'.PHP_EOL;
    }
}
?>
<link rel="canonical" href="<?php echo no_query_string(get_current_url(), ['page']) ?>">

<title><?php echo get_meta('title', __('Mallow')) ?></title>
<link rel="stylesheet" href="<?php echo url('/vendor/bootstrap/css/bootstrap.min.css') ?>">
