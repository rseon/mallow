<h1><?php echo __('Home') ?></h1>

<p><?php echo __('Hello, :name !', compact('name')) ?></p>

<p><a href="<?php echo route('index.test', ['id' => 4]) ?>"><?php echo __('Localized route') ?></a></p>