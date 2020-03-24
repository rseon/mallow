<div class="container">
    <h1><?php echo __('Home') ?></h1>

    <p><?php echo __('Hello, :name !', ['name' => $this->name]) ?></p>

    <form action="<?php echo route('testform', [], 'POST') ?>" method="post">
        <?php echo csrf_input() ?>

        <input type="text" class="form-control<?php if(error('name')) echo ' is-invalid' ?>" name="name" placeholder="Your name" value="<?php echo old('name') ?>" />
        <input type="text" class="form-control<?php if(error('email')) echo ' is-invalid' ?>" name="email" placeholder="Your email" value="<?php echo old('email') ?>" />
        <button type="submit" class="btn btn-primary">Test</button>
    </form>
</div>