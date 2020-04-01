<header class="main-header">
    <a href="<?php echo admin_url('/') ?>" class="logo">
        <span class="logo-mini"><b>A</b> P</span>
        <span class="logo-lg"><b>Admin</b> panel</span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php $this->partial('admin.layouts.share.header.messages'); ?>
                <?php $this->partial('admin.layouts.share.header.notifications'); ?>
                <?php $this->partial('admin.layouts.share.header.tasks'); ?>
                <?php $this->partial('admin.layouts.share.header.user', $this->getAttributes()); ?>
            </ul>
        </div>
    </nav>
</header>