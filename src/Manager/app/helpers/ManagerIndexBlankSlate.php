<?php
namespace Helper\Manager;

class ManagerIndexBlankSlate {
    public function render (Array $args, Array $options) {
        $metadata = $options['metadata'];
        return '
            <div class="row">
                <div class="column">
                    <div class="ui icon blue message">
                        <i class="ui icon ' . $metadata['icon'] . '" style="vertical-align: top"></i>
                        <div class="content">
                            <div class="header">This section is currently empty.</div>
                            <p>To add the first “' . $metadata['singular'] . '”, click the button below.
                            <br>
                            <br>
                            <div class="ui icon button primary manager add">
                                <i class="' . $metadata['icon'] . ' icon"></i> Add ' . $metadata['singular'] . '
                            </div>
                         </div>
                    </div>
                </div>
            </div>';
    }
}