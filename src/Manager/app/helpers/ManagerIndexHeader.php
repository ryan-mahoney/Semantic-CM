<?php
namespace Helper\Manager;

class ManagerIndexHeader {
    public function render (Array $args, Array $options) {
        $buffer = '';
        $metadata = $options['metadata'];
        $pagination = $options['pagination'];
        $stop = $pagination['page'] * $pagination['limit'];
        if ($pagination['total'] < $stop) {
            $stop = $pagination['total'];
        }
        $start = (($pagination['page'] -1) * $pagination['limit']) + 1;
        $buffer .= '
            <div id="manager-gray-bar">
                <div class="ui page grid">
                    <div class="row">
                        <div class="column">
                            <div id="manager-breadcrumbs" class="ui large breadcrumb">
                                <a class="section" href="/Manager">Dashboard</a>
                                <i class="right chevron icon divider"></i>
                                <a class="section" href="/Manager/section/' . $metadata['category'] . '">' . $metadata['category'] . '</a>
                                <i class="right chevron icon divider"></i>
                                <div class="active section">' . $metadata['title'] . '</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui page grid">
                <div class="row">
                    <div class="column">
                        <h1 id="manager-index-header" class="ui header">' . $metadata['title'] . '</h1>';
                        if ($pagination['total'] > 0) {
                            $buffer .= '<div id="manager-index-count">' . $start . '-' . $stop . ' of ' . $pagination['total'] . '</div>';
                        }
                        $buffer .= '
                        <div class="ui ignored divider manager-index-description"></div>
                    </div>
                </div>
            </div>';

        return $buffer;
    }
}