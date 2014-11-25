<?php
namespace Helper\Manager;

class ManagerField {

    public function render (Array $args, Array $options) {
        if (!isset($args[0])) {
            return '<!-- Missing context -->';
        }
        if (!isset($options['name'])) {
            return '<!-- Name not specified -->';
        }
        if (empty($options['name']) || !is_array($args[0])) {
            return '<!-- problem -->';
        }
        $markup = $args[0][$options['name']];
        $required = false;
        if (in_array('required', $options)) {
            $required = true;
            $args = array_diff($args, array('required'));
        }
        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        $label = false;
        $labeled = '';
        if (array_key_exists('label', $options)) {
            $label = $options['label'];
            $labeled = ' labeled ';
        }
        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        return '
            <div class="field" data-field="' . $options['name'] . '">' .
                ($label !== false ? '<label>' . $label . '</label>' : '') .
                '<div class="ui ' . $options['class'] . ' ' . $labeled . ' input">' .
                    $markup .
                    ($required !== false ? '<div class="ui corner label"><i class="icon asterisk"></i></div>' : '') . 
                '</div>
            </div>';
    }
}