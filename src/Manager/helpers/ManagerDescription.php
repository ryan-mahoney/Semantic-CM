<?php
namespace Helper\Manager;

class ManagerDescription {
    public function render (Array $args, Array $options) {
        $manager = $args[0];
        return $manager['count'] . ' ' . (($manager['count'] == 1) ? $manager['singular'] : $manager['title']);
    }
}