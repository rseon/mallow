<?php
if(!flash()->isEmpty()) {
    foreach(flash()->get() as $type => $messages) {
        ?>
        <div class="alert alert-<?php echo $type ?> alert-dismissible" role="alert">
            <?php
            echo implode('<br>', $messages);
            ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
    }
}