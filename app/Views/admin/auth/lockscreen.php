<div class="lockscreen">
    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
            <b>Admin</b> panel
        </div>
        <div class="lockscreen-name"><?php echo $this->user['name'] ?></div>

        <div class="lockscreen-item">
            <div class="lockscreen-image">
                <!--img src="<?php echo url('/vendor/adminlte/img/user1-128x128.jpg') ?>" alt="User Image"-->
                <img src="https://eu.ui-avatars.com/api/?name=<?php echo urlencode($this->user['name']) ?>" alt="User Image">
            </div>

            <form class="lockscreen-credentials" action="<?php echo admin_url('/auth/lockscreen') ?>" method="post">
                <?php echo csrf_input() ?>

                <div class="input-group">
                    <input type="password" class="form-control" name="password" placeholder="Mot de passe">

                    <div class="input-group-btn">
                        <button type="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
                    </div>
                </div>
            </form>

        </div>
        <div class="help-block text-center">
            Entrez votre mot de passe pour retrouver votre session
        </div>
        <div class="text-center">
            <a href="<?php echo admin_url('/auth/login') ?>">Changer d'utilisateur</a>
        </div>
    </div>
</div>