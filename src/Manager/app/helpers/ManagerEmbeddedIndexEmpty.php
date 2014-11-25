<?php
namespace Helper\Manager;

class ManagerEmbeddedIndexEmpty {
    public function render (Array $args, Array $options) {
        //usage: {{#EmbeddedCollectionEmpty singular="Category"}}
        $singular = '';
        if (isset($options['singular'])) {
            $singular = $options['singular'];
        }
        return '<div class="ui message">
                <div class="content">
                    <div class="header">This section is currently empty.</div>
                      <p>To add the first “' . $singular . '”, click the button on the right.</p>
                 </div>
            </div>';
    }
}