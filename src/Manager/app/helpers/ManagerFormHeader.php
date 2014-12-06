<?php
namespace Helper\Manager;

class ManagerFormHeader {

    public function render (Array $args, Array $options) {
        $metadata = $options['metadata'];
        return '
            <div class="ui grid">
                <div class="row">
                    <div class="column">
                        <div id="manager-breadcrumbs" class="ui large breadcrumb">
                            <a class="section" href="/Manager">Dashboard</a>
                            <i class="right arrow icon divider"></i>
                            <a class="section" href="/Manager?' . $metadata['category'] . '">' . $metadata['category'] . '</a>
                            <i class="right arrow icon divider"></i>
                            <a class="section" href="/Manager/index/' . $metadata['link'] . '">' . $metadata['title'] . '</a>
                            <i class="right arrow icon divider"></i>
                            <div class="active section">' . $metadata['singular'] . '</div>
                        </div>
                        <div class="ui ignored divider manager-index-description"></div>
                    </div>
                </div>
            </div>';

        return $buffer;
    }
}