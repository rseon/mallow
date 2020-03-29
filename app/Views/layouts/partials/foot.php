<script src="<?php echo url('/js/manifest.js') ?>"></script>
<script src="<?php echo url('/js/vendor.js') ?>"></script>
<script src="<?php echo url('/js/app.js') ?>"></script>

<?php
if (getenv('APP_DEBUG')) {
    $debugbar = registry('Debugbar');
    $debugbar['time']->stopMeasure('App');
    echo $debugbar->getJavascriptRenderer()->render();
}
