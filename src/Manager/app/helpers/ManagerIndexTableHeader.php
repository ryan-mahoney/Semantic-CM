<?php
namespace Helper\Manager;

class ManagerIndexTableHeader {
    public function render (Array $args, Array $options) {
        $metadata = $options['metadata'];
        $pagination = $options['pagination'];
        $startPage = $pagination['page'] - 4;
        $endPage = $pagination['pageCount'] + 4;
        $columns = 2;
        if (isset($options['columns'])) {
            $columns = $options['columns'];
        }
        $buffer = '
                        <div class="ui two column grid">
                            <div class="column">';

        if ($startPage <= 0) {
            $endPage -= ($startPage - 1);
            $startPage = 1;
        }
        if ($endPage > $pagination['pageCount']) {
            $endPage = $pagination['pageCount'];
        }

        $buffer .= '
                                <div class="ui borderless pagination menu small">';
        if ($startPage > 1) {
            $buffer .= '
                                    <a class="item">
                                        <i class="icon left arrow"></i>
                                    </a>';
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            $active = '';
            if ($i == $pagination['page']) {
                $active = ' active';
            }
            $buffer .= '
                                    <a class="item' . $active . '">' . $i . '</a>';
        }
        if ($endPage < $pagination['pageCount']) {
            $buffer .= '
                                    <a class="item">
                                        <i class="icon right arrow"></i>
                                    </a>';
        }
        $buffer .= '
                                </div>
                            </div>
                            <div class="column" style="text-align: right">
                                <div id="manager-index-add" class="ui primary large buttons">
                                    <div class="ui icon button manager add">
                                        <i class="' . $metadata['icon'] . ' icon"></i> Add ' . $metadata['singular'] . '
                                    </div>
                                    <div class="ui combo top right pointing dropdown icon button">
                                        <i class="dropdown icon"></i>
                                        <div class="menu">
                                            <div class="item"><i class="cloud download icon"></i> Export</div>
                                            <div class="item"><i class="filter icon"></i> Filter</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';

        return $buffer;
    }
}