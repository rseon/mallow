<?php
if(!flash()->isEmpty()) {
    foreach(flash()->get() as $type => $messages) {
        $this->partial('admin.layouts.components.alert', [
            'type' => $type,
            'message' => $messages,
            'dismissible' => true,
        ]);
    }
}