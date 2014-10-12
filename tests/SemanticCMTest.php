<?php
namespace Opine;
use PHPUnit_Framework_TestCase;

class SemanticCMTest extends PHPUnit_Framework_TestCase {
    private $db;

    public function setup () {
        date_default_timezone_set('UTC');
        $root = __DIR__;
        $container = new Container($root, $root . '/container.yml');
        $this->db = $container->db;
    }

    public function testSample () {
        $this->assertTrue(true);
    }
}