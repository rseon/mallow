<h1>Connexion à l'administration</h1>

<form action="<?php echo admin_url('/auth/login') ?>" method="post">
    <?php echo csrf_input() ?>

    <input type="text" class="form-control<?php if(error('username')) echo ' is-invalid' ?>" name="username" placeholder="Identifiant" value="<?php echo old('username') ?>" />
    <input type="password" class="form-control<?php if(error('password')) echo ' is-invalid' ?>" name="password" placeholder="Mot de passe" />
    <button type="submit" class="btn btn-primary">Connexion</button>
</form>