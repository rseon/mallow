<script src="<?php echo url('/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?php echo url('/vendor/popper.js/popper.min.js') ?>"></script>
<script src="<?php echo url('/vendor/bootstrap/js/bootstrap.min.js') ?>"></script>

<?php
if (getenv('APP_DEBUG') === 'true') {
    $debugbar = container('Debugbar');
    $debugbar['time']->stopMeasure('App');
    echo $debugbar->getJavascriptRenderer()->render();
}
