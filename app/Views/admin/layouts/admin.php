<!DOCTYPE html>
<html>
<head>
    <?php $this->partial('admin.layouts.partials.head'); ?>
</head>
<body class="hold-transition skin-black fixed">
<div class="wrapper">
    <?php $this->partial('admin.layouts.share.header', $this->getAttributes()); ?>
    <?php $this->partial('admin.layouts.share.sidebar', $this->getAttributes()); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <?php
            $this->partial('admin.layouts.components.page_header', $this->getAttributes());

            $this->partial('admin.layouts.components.breadcrumbs', $this->getAttributes());
            ?>
        </section>

        <section class="content container-fluid">
            <?php $this->partial('admin.layouts.components.flash'); ?>
            <?php echo $this->content(); ?>
        </section>
    </div>
    <?php $this->partial('admin.layouts.share.footer'); ?>
</div>
<?php $this->partial('admin.layouts.partials.foot'); ?>
</body>
</html>
