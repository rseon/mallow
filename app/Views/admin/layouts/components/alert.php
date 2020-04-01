<?php
$type = $this->type ?? 'info';
$message = $this->message ?? 'Message d\'alerte';
$dismissible = isset($this->dismissible) ? ' alert-dismissible' : '';
?>
<div class="alert alert-<?php echo $type . $dismissible ?>" role="alert">
    <?php
    if($dismissible) {
        ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php
    }

    if(is_array($message)) {
        foreach($message as $m) {
            if(is_array($m)) {
                echo implode('<br>', $m).'<br>';
            }
            else {
                echo $m . '<br>';
            }
        }

    }
    else {
        echo $message;
    }
    ?>
</div>
<?php

