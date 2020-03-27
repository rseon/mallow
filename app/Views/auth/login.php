<h1><?php echo __('Login') ?></h1>

<form action="<?php echo route('login') ?>" method="post">
    <?php echo csrf_input() ?>

    <input type="email" class="form-control<?php if(error('email')) echo ' is-invalid' ?>" name="email" placeholder="<?php echo __('Email address') ?>" value="<?php echo old('email') ?>" />
    <input type="password" class="form-control<?php if(error('password')) echo ' is-invalid' ?>" name="password" placeholder="<?php echo __('Password') ?>" />
    <button type="submit" class="btn btn-primary"><?php echo __('Login') ?></button>
</form>