<form action="<?php echo $this->action ?>" method="post">
    <?php echo csrf_input() ?>

    <div class="box">
        <div class="box-body">

            <div class="form-group has-feedback<?php if(error('name')) echo ' has-error' ?>">
                <label><span class="text-danger">*</span> Nom :</label>
                <input type="text" name="name" value="<?php echo old('name') ?? $this->user->name ?? '' ?>" class="form-control">
            </div>

            <div class="form-group has-feedback<?php if(error('email')) echo ' has-error' ?>">
                <label><span class="text-danger">*</span> Email :</label>
                <input type="text" name="email" value="<?php echo old('email') ?? $this->user->email ?? '' ?>" class="form-control">
            </div>

        </div>
        <div class="box-footer">
            <input type="submit" id="submit" value="Enregistrer" class="btn btn-success btn-lg">
        </div>
    </div>
</form>