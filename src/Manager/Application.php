<?php
namespace Manager;

class Application {
	private $slim;
	private $authentication;
	private $separation;
	private $response;
	private $root;

	public function __construct ($container, $root) {
		$this->slim = $container->slim;
		$this->authentication = $container->authentication;
		$this->separation = $container->separation;
		$this->response = $container->response;
		$this->root = $root;
	}

	public function app () {
		$this->slim->get('/Manager', function () {
			$this->authenticate();
			$this->separation->app('bundles/Manager/app/dashboard')->layout('Manager/dashboard')->template()->write($this->response->body);
		});

		$this->slim->get('/Manager/login', function () {
			if ($this->authentication->valid('Manager') !== false) {
				header('Location: /Manager');
			}
			$this->separation->app('bundles/Manager/app/forms/login')->layout('Manager/forms/login')->template()->write($this->response->body);
		});

		$this->slim->get('/Manager/logout', function () {
			$this->authentication->logout('Manager');
			$this->authenticate();
		});

		$this->slim->get('/Manager/api/managers', function () {
			$userId = false;
			if (isset($_GET['userId'])) {
				$userId = $_GET['userId'];
			}
			$managersCacheFile = $this->root . '/../managers/cache.json';
			if (!file_exists($managersCacheFile)) {
				$this->response->body = json_encode(['managers' => []]);
				return;	
			}
			if ($userId===false) {
				$this->response->body = file_get_contents($managersCacheFile);
			}
			//get user's acl for manager
			//filter out managers he user does not have access to
		});

		$this->slim->get('/Manager/api/search', function () {
			//elasic search
		});
	}

	private function authenticate () {
		if ($this->authentication->valid('Manager') === false) {
			header('Location: /Manager/login');
			exit;
		}
		return true;
	}

	public function build ($root) {
		$managersRoot = $root . '/../managers';
		$managersCacheFile = $managersRoot . '/cache.json';
		$managers = [];
		if (!file_exists($managersRoot)) {
			file_put_contents($managersCacheFile, json_encode(['managers' => $managers]));
			return;
		}
		$dirFiles = glob($managersRoot . '/*.php');
		if (!is_array($dirFiles) || empty($dirFiles)) {
			file_put_contents($managersCacheFile, json_encode(['managers' => $managers]));
			return;
		}
		foreach ($dirFiles as $managerClassFile) {
			$manager = basename($managerClassFile, '.php');
			require_once ($managerClassFile);
			$managerClassName = 'Manager\\' . $manager;
			if (!class_exists($managerClassName, false)) {
				echo 'Problem: Manager build: ', $managerClassName, ': missing "Manager" namespace or type in class delcaration.', "\n\n";
				continue;
			}
			$managerInstance = new $managerClassName();
			$record = [
				'titleCard' => $managerInstance->titleCard,
				'decriptionCard' => $managerInstance->decriptionCard,
				'acl' => $managerInstance->acl,
				'icon' => $managerInstance->icon,
				'category' => $managerInstance->category
			];
			$managers[] = $record;
		}
		file_put_contents($managersCacheFile, json_encode(['managers' => $managers], JSON_PRETTY_PRINT));
	}
}