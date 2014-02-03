<?php
return function ($template, $context, $args, $source) {
    //usage: {{#EmbeddedCollectionHeader label="Some Label"}}
    $args = $template->parseTagAttributes($args);
    $label = $args['label'];
    return '
        <a class="item">' . $label . '</a>
        <div class="item right">
            <div class="ui button manager add">Add</div>
        </div>';
};