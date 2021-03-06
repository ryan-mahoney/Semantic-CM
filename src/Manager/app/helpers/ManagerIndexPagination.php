<?php
namespace Helper\Manager;

class ManagerIndexPagination
{
    public function render(Array $args, Array $options)
    {
        $buffer = '';
        $pagination = $options['pagination'];
        //if ($pagination['pageCount'] == 1) {
        //    return '';
        //}
        $startPage = $pagination['page'] - 4;
        $endPage = $pagination['pageCount'] + 4;

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
                <a class="item'.$active.'">'.$i.'</a>';
        }
        if ($endPage < $pagination['pageCount']) {
            $buffer .= '
                <a class="item">
                    <i class="icon right arrow"></i>
                </a>';
        }
        $buffer .= '
            </div>';

        return $buffer;
    }
}
