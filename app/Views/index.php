<h1><?php echo __('Home') ?></h1>

<p><?php echo __('Hello, :name !', ['name' => $this->name]) ?></p>

<?php
if(isset($this->id)) {
    echo "<p>ID = {$this->id}</p>";
}
if(isset($this->request)) {
    dump($this->request);
}
?>