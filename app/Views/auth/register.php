<h1><?php echo __('Register') ?></h1>

<form action="<?php echo route('register') ?>" method="post">
    <?php echo csrf_input() ?>

    <input type="text" class="form-control<?php if(error('name')) echo ' is-invalid' ?>" name="name" placeholder="<?php echo __('Name') ?>" value="<?php echo old('name') ?>" />
    <input type="email" class="form-control<?php if(error('email')) echo ' is-invalid' ?>" name="email" placeholder="<?php echo __('Email address') ?>" value="<?php echo old('email') ?>" />
    <input type="password" class="form-control<?php if(error('password')) echo ' is-invalid' ?>" name="password" placeholder="<?php echo __('Password') ?>" />
    <input type="password" class="form-control<?php if(error('password')) echo ' is-invalid' ?>" name="password_confirm" placeholder="<?php echo __('Confirm password') ?>" />
    <button type="submit" class="btn btn-primary"><?php echo __('Register') ?></button>
</form>