<?php
return function ($args, $named) {
    $metadata = $named['metadata'];
    return '
        <div class="ui icon yellow message">
            <i class="ui icon ' . $metadata['icon'] . '" style="vertical-align: top"></i>
            <div class="content">
                <div class="header">This section is currently empty.</div>
                  <p>To add the first “' . $metadata['singular'] . '”, click the button below.<br /><div class="ui teal medium button manager add">Add</div></p>
             </div>
        </div>';
};