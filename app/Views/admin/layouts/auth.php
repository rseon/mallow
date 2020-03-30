<!DOCTYPE html>
<html>
<head>
    <?php $this->partial('admin.layouts.partials.head'); ?>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <?php $this->partial('admin.layouts.components.flash'); ?>
        <?php echo $this->content(); ?>
    </div>
    <?php $this->partial('admin.layouts.partials.foot'); ?>
</body>
</html>
