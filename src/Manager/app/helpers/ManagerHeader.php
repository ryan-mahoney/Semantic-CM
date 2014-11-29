<?php
namespace Helper\Manager;

use Opine\Interfaces\Layout as LayoutInterface;

class ManagerHeader {
    private $layout;

    public function __construct (LayoutInterface $layout) {
        $this->layout = $layout;
    }

    public function render (Array $args, Array $options) {
        return $this->layout->make('Manager/header');
    }
}