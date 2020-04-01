<script src="<?php echo url('/vendor/adminlte/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?php echo url('/vendor/adminlte/vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo url('/vendor/adminlte/js/adminlte.min.js') ?>"></script>
<script src="<?php echo url('/vendor/adminlte/vendor/jquery-slimscroll/jquery.slimscroll.min.js') ?>"></script>
<script src="<?php echo url('/vendor/adminlte/vendor/fastclick/lib/fastclick.js') ?>"></script>

<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<?php
if (getenv('APP_DEBUG')) {
    $debugbar = registry('Debugbar');
    $debugbar['time']->stopMeasure('App');
    echo $debugbar->getJavascriptRenderer()->render();
}
?>