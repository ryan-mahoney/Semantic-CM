<?php
return function ($args, $named) {
    //usage: {{#EmbeddedCollectionEmpty singular="Category"}}
    $singular = '';
    if (isset($named['singular'])) {
        $singular = $named['singular'];
    }
    return '<div class="ui message">
            <div class="content">
                <div class="header">This section is currently empty.</div>
                  <p>To add the first “' . $singular . '”, click the button on the right.</p>
             </div>
        </div>';
};