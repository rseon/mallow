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