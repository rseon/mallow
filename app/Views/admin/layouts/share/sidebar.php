<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <!--img src="<?php echo url('/vendor/adminlte/img/user2-160x160.jpg') ?>" class="img-circle" alt="User Image"-->
                <img src="https://eu.ui-avatars.com/api/?name=<?php echo urlencode($this->user['name']) ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <!--p>Alexander Pierce</p-->
                <p><?php echo $this->user['name'] ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
            </div>
        </form>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">NAVIGATION</li>
            <li<?php echo !$this->layout_active_menu ? ' class="active"' : '' ?>>
                <a href="<?php echo admin_url('/') ?>">
                    <i class="fa fa-tachometer"></i> <span>Tableau de bord</span>
                </a>
            </li>
            <li class="header">ADMINISTRATION</li>
            <li<?php echo $this->layout_active_menu === 'user' ? ' class="active"' : '' ?>>
                <a href="<?php echo admin_url('/user/index') ?>">
                    <i class="fa fa-users"></i> <span>Utilisateurs</span>
                </a>
            </li>
            <!--
            <li><a href="#"><i class="fa fa-link"></i> <span>Link</span></a></li>
            <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li>
            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
                    <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#">Link in level 2</a></li>
                    <li><a href="#">Link in level 2</a></li>
                </ul>
            </li>
            -->
        </ul>
    </section>
</aside>