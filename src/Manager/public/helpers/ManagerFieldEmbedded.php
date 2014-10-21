<?php
return function ($args, $named) {
    //usage: {{FieldEmbedded name="parent-field-name" required="true" manager="name-of-manager-to-embed" label="what to display"}}
    $field = $named['name'];
    $required = false;
    if (isset($named['required']) && $named['required'] == true) {
        $required = true;
    }
    $manager = $named['manager'];
    $markup = $args[0][$named['name']];
    $label = false;
    if (isset($named['label'])) {
        $label = $named['label'];
    }
    if (empty($markup)) {
        return '<div class="field embedded" data-field="' . $field . '" data-manager="' . $manager . '"><div class="ui message">' . $label . ' can be added after save.</div></div>';
    }

    return '<div class="field embedded" data-field="' . $field . '" data-manager="' . $manager . '">' . $markup . '</div>';
};