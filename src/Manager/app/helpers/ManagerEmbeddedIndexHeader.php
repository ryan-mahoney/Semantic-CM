<?php
namespace Helper\Manager;

class ManagerEmbeddedIndexHeader
{
    public function render(Array $args, Array $options)
    {
        return '
            <label>'.$options['label'].'</label>
            <div class="item right">
                <div class="ui button manager add">Add</div>
            </div>';
    }
}
