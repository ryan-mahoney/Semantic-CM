<?php
require_once __DIR__ . '/../vendor/autoload.php';

$root = __DIR__ . '/../public';
$container = new \Opine\Container($root, $root . '/../container.yml');
$managerRoute = $container->managerRoute;
$managerRoute->paths();
print_r($container->route->show());

foreach (glob($root . '/../managers/*.php') as $filename) {
	require_once $filename;
}

$bundleModel = $container->bundleModel;
$bundleModel->build();
$managerModel = $container->managerModel;
$managerModel->build();