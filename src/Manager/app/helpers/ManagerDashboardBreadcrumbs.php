<?php
namespace Helper\Manager;

class ManagerDashboardBreadcrumbs {
    private $layout;

    public function render (Array $args, Array $options) {
        if (!isset($_SERVER['REQUEST_URI'])) {
            return '';
        }
        $uri = $_SERVER['REQUEST_URI'];
        if (substr_count($uri, '?') > 0) {
            $tmp = explode('?', $uri, 2);
            $uri = $tmp[0];
        }
        if (substr_count($uri, '/section/') < 1) {
            return '<div class="active section">Dashboard</div>';
        }
        $name = str_replace('/Manager/section/', '', $uri);
        return '
            <a href="/Manager" class="section">Dashboard</a>
            <i class="right chevron icon divider"></i>
            <div class="active section">' . $name . '</div>';
    }
}