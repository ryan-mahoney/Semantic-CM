<?php
namespace Helper\Manager;

class ManagerFieldEmbedded
{
    public function render(Array $args, Array $options)
    {
        //usage: {{FieldEmbedded name="parent-field-name" required="true" manager="name-of-manager-to-embed" label="what to display"}}
        $field = $options['name'];
        $required = false;
        if (isset($options['required']) && $options['required'] == true) {
            $required = true;
        }
        $manager = $options['manager'];
        $markup = $args[0][$options['name']];
        $label = false;
        if (isset($options['label'])) {
            $label = $options['label'];
        }
        if (empty($markup)) {
            return '<div class="field embedded" data-field="'.$field.'" data-manager="'.$manager.'"><div class="ui message">'.$label.' can be added after save.</div></div>';
        }

        return '<div class="field embedded" data-field="'.$field.'" data-manager="'.$manager.'">'.$markup.'</div>';
    }
}
