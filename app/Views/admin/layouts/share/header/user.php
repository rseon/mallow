<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <!--img src="<?php echo url('/vendor/adminlte/img/user2-160x160.jpg') ?>" class="user-image" alt="User Image"-->
        <img src="https://eu.ui-avatars.com/api/?name=<?php echo urlencode($this->user['name']) ?>" class="user-image" alt="User Image">
        <!--span class="hidden-xs">Alexander Pierce</span-->
        <span class="hidden-xs"><?php echo $this->user['name'] ?></span>
    </a>
    <ul class="dropdown-menu">
        <li class="user-header">
            <!--img src="<?php echo url('/vendor/adminlte/img/user2-160x160.jpg') ?>" class="img-circle" alt="User Image"-->
            <img src="https://eu.ui-avatars.com/api/?name=<?php echo urlencode($this->user['name']) ?>" class="img-circle" alt="User Image">

            <p>
                <!--Alexander Pierce - Web Developer-->
                <?php echo $this->user['name'] ?> - <?php echo $this->user['email'] ?>
                <!--small>Member since Nov. 2012</small-->
                <small>Member since <?php echo $this->user['created_at']->format('M Y') ?></small>
            </p>
        </li>
        <li class="user-body">
            <div class="row">
                <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                </div>
                <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                </div>
                <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                </div>
            </div>
        </li>
        <li class="user-footer">
            <div class="pull-left">
                <a href="#" class="btn btn-default btn-flat">Profile</a>
            </div>
            <div class="pull-right">
                <a href="<?php echo admin_url('/auth/logout') ?>" class="btn btn-default btn-flat">Sign out</a>
            </div>
        </li>
    </ul>
</li>
