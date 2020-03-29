<script src="<?php echo asset('/js/manifest.js') ?>"></script>
<script src="<?php echo asset('/js/vendor.js') ?>"></script>
<script src="<?php echo asset('/js/app.js') ?>"></script>

<?php
if (getenv('APP_DEBUG')) {
    $debugbar = registry('Debugbar');
    $debugbar['time']->stopMeasure('App');
    echo $debugbar->getJavascriptRenderer()->render();
}
