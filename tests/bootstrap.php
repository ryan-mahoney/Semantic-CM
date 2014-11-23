<?php
date_default_timezone_set('UTC');

require_once __DIR__ . '/../vendor/autoload.php';

$root = __DIR__ . '/../public';
$config = new \Opine\Config\Service($root);
$config->cacheSet();
$container = new \Opine\Container\Service($root, $config, $root . '/../container.yml');
$managerRoute = $container->get('managerRoute');
$managerRoute->paths();
print_r($container->get('route')->show());

foreach (glob($root . '/../managers/*.php') as $filename) {
	require_once $filename;
}

$bundleModel = $container->get('bundleModel');
$bundleModel->build();
$managerModel = $container->get('managerModel');
$managerModel->build();