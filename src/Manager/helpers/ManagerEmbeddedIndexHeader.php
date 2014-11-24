<?php
namespace Helper\Manager;

class ManagerEmbeddedIndexHeader {
    public function render (Array $args, Array $options) {
        //usage: {{#EmbeddedCollectionHeader label="Some Label"}}
        return '
            <a class="item">' . $options['label'] . '</a>
            <div class="item right">
                <div class="ui button manager add">Add</div>
            </div>';
    }
}