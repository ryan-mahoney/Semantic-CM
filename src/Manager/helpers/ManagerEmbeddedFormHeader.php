<?php
namespace Helper\Manager;

class ManagerEmbeddedFormHeader {
    public function render (Array $args, Array $options) {
        $metadata = $options['metadata'];
        return '
            <div class="header">' . $metadata['singular'] . '</div>
            <form data-xhr="true" method="post" action="/Manager/api/upsert/' . $metadata['link'] . '" data-manager="' . $metadata['link'] . '" data-class="' . str_replace('\\', '__', $metadata['class']) . '">
                <div class="ui divided grid">
                    <div class="row">
                        <div class="sixteen wide column manager embedded ui form">';
    }
}