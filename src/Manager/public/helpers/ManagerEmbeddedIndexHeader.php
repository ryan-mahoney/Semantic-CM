<?php
return function ($args, $named) {
    //usage: {{#EmbeddedCollectionHeader label="Some Label"}}
    return '
        <a class="item">' . $named['label'] . '</a>
        <div class="item right">
            <div class="ui button manager add">Add</div>
        </div>';
};