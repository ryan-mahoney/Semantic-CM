<?php
return function ($template, $context, $args, $source) {
    //usage: {{#EmbeddedCollectionEmpty singular="Category"}}
    $args = $template->parseTagAttributes($args);
    $singular = '';
    if (isset($args['singular'])) {
        $singular = $args['singular'];
    }
    return '<div class="ui message">
            <div class="content">
                <div class="header">This section is currently empty.</div>
                  <p>To add the first “' . $singular . '”, click the button on the right.</p>
             </div>
        </div>';
};