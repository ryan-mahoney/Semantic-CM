<?php
return function ($args, $named) {
    $metadata = $named['metadata'];
    return '
        <div class="manager table buttons">
            <!-- <div class="ui small button export">Export</div> -->
            <!-- <div class="ui small button filter">Filter</div> -->
            <div class="ui teal medium button manager add">Add</div>
        </div>';
};