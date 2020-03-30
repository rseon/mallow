<?php
if(isset($this->layout_header)) {
    ?>
    <h1>
        <?php echo $this->layout_header; ?>
        <small><?php echo $this->layout_subheader ?? null; ?></small>
    </h1>
<?php
}