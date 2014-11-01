<?php
return function ($args, $named) {
    $manager = $args[0];
    return $manager['count'] . ' ' . (($manager['count'] == 1) ? $manager['singular'] : $manager['title']);  
};