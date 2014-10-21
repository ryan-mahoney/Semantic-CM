<?php
return function ($args, $named) {
    if (!isset($args[0])) {
        return '<!-- Missing context -->';
    }
    if (!isset($named['name'])) {
        return '<!-- Name not specified -->';
    }
    if (empty($named['name']) || !is_array($args[0])) {
        return '<!-- problem -->';
    }
    $markup = $args[0][$named['name']];
    $required = false;
    if (in_array('required', $named)) {
        $required = true;
        $args = array_diff($args, array('required'));
    }
    if (!isset($named['class'])) {
        $named['class'] = '';
    }
    $label = false;
    $labeled = '';
    if (array_key_exists('label', $named)) {
        $label = $named['label'];
        $labeled = ' labeled ';
    }
    if (!isset($named['class'])) {
        $named['class'] = '';
    }
    return '
        <div class="field" data-field="' . $named['name'] . '">' .
            ($label !== false ? '<label>' . $label . '</label>' : '') .
            '<div class="ui ' . $named['class'] . ' ' . $labeled . ' input">' .
                $markup .
                ($required !== false ? '<div class="ui corner label"><i class="icon asterisk"></i></div>' : '') . 
            '</div>
        </div>';
};