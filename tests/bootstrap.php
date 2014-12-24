<?php
date_default_timezone_set('UTC');

require_once __DIR__ . '/../vendor/autoload.php';

$root = __DIR__ . '/../public';

$config = new \Opine\Config\Service($root);
$config->cacheSet();
$container = \Opine\Container\Service::instance($root, $config, $root . '/../config/containers/test-container.yml');
$managerRoute = $container->get('managerRoute');
$managerRoute->paths();
$bundleModel = $container->get('bundleModel');
$bundleModel->build();
$managerModel = $container->get('managerModel');
$managerModel->build();