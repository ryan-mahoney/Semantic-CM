<?php
namespace Helper\Manager;

class ManagerFormMainColumn {
    public function render (Array $args, Array $options) {
        return '
            <div class="ui grid">
                <div id="manager-form-row" class="row">
                    <div class="column manager main ui form">';
    }
}