<div class="login-logo">
    <b>Admin</b>istration
</div>
<div class="login-box-body">
    <form action="<?php echo admin_url('/auth/login') ?>" method="post">
        <?php echo csrf_input() ?>

        <div class="form-group has-feedback<?php if(error('username')) echo ' has-error' ?>">
            <input type="text" class="form-control" name="username" placeholder="Identifiant" value="<?php echo old('username') ?>">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback<?php if(error('password')) echo ' has-error' ?>">
            <input type="password" class="form-control" name="password" placeholder="Mot de passe">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" name="remember" value="1"<?php echo old('remember') ? ' checked' : '' ?>> Se souvenir de moi
                    </label>
                </div>
            </div>
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Connexion</button>
            </div>
        </div>
    </form>
</div>
