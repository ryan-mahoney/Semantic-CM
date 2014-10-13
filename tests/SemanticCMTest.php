<?php
namespace Opine;
use PHPUnit_Framework_TestCase;

/**
 * @backupGlobals disabled
 */
class SemanticCMTest extends PHPUnit_Framework_TestCase {
    public function setup () {
        date_default_timezone_set('UTC');
        $root = __DIR__ . '/../public';
        $container = new Container($root, $root . '/../container.yml');
        $this->route = $container->route;
        $this->managerRoute = $container->managerRoute;
        $this->route->testMode();
        $this->managerRoute->paths();
    }

    public function testApiManagersNoSession () {
        $_SESSION['user']['groups'] = [];
        $json = json_decode($this->route->run('GET', '/Manager/api/managers'), true);
        $this->assertTrue(count($json['managers']) == 0);
    }

    public function testApiManagersSession () {
        $_SESSION['user']['groups'] = ['manager'];
        $json = json_decode($this->route->run('GET', '/Manager/api/managers'), true);
        $this->assertTrue(count($json['managers']) > 0);
    }
    
    public function testHeader () {
        $response = $this->route->run('GET', '/Manager/header');
        $this->assertTrue(substr_count($response, '<strong>Manager:</strong>') > 0);
    }
}