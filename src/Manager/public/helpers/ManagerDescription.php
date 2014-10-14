<?php
return function ($args, $named) {
    $manager = $args[0];
    return str_replace('{{count}}', $manager['count'], $manager['description']);
};