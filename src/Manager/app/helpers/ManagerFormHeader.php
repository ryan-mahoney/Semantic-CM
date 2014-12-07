<?php
namespace Helper\Manager;

class ManagerFormHeader {

    public function render (Array $args, Array $options) {
        $metadata = $options['metadata'];
        return '
            <div id="manager-gray-bar">
                <div class="ui page grid">
                    <div class="row">
                        <div class="column">
                            <div id="manager-breadcrumbs" class="ui large breadcrumb">
                                <a class="section" href="/Manager">Dashboard</a>
                                <i class="right arrow icon divider"></i>
                                <a class="section" href="/Manager/section/' . $metadata['category'] . '">' . $metadata['category'] . '</a>
                                <i class="right arrow icon divider"></i>
                                <a class="section" href="/Manager/index/' . $metadata['link'] . '">' . $metadata['title'] . '</a>
                                <i class="right arrow icon divider"></i>
                                <div class="active section">' . $metadata['singular'] . '</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="manager-form-notice" class="ui positive message">
                <div class="ui page grid">
                    <div class="row">
                        <div class="column form-notice"><h1 class="ui header"></h1></div>
                    </div>
                </div>
            </div>';

        return $buffer;
    }
}