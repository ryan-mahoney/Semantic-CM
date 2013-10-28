<?php
namespace Manager;

class Application {
	private $slim;
	private $authentication;
	private $separation;
	private $response;

	public function __construct ($container) {
		$this->slim = $container->slim;
		$this->authentication = $container->authentication;
		$this->separation = $container->separation;
		$this->response = $container->response;
	}

	public function app () {
		$this->slim->get('/Manager', function () {
			$this->authenticate();
			echo 'Manager Dashboard';
		});

		$this->slim->get('/Manager/login', function () {
			$this->separation->app('bundles/Manager/app/forms/login')->layout('Manager/forms/login')->template()->write($this->response->body);
		});
	}

	private function authenticate () {
		if ($this->authentication->valid('manager') === false) {
			header('Location: /Manager/login');
			exit;
		}
		return true;
	}
}