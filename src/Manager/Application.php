<?php
namespace Manager;

class Application {
	private $slim;
	private $authentication;
	private $separation;
	private $response;
	private $root;
	private $bundleRoot;

	public function __construct ($container, $root, $bundleRoot) {
		$this->slim = $container->slim;
		$this->authentication = $container->authentication;
		$this->separation = $container->separation;
		$this->response = $container->response;
		$this->root = $root;
		$this->bundleRoot = $bundleRoot;
	}

	public function app () {
		$this->slim->get('/Manager', function () {
			$this->authenticate();
			$this->separation->app('bundles/Manager/app/dashboard')->layout('Manager/dashboard')->template()->write($this->response->body);
		});

		$this->slim->get('/Manager/header', function () {
			$this->separation->
				app('bundles/Manager/app/header')->
				layout('Manager/header')->
				template()->
				write($this->response->body);
		});

		$this->slim->get('/Manager/add(/:name)', function ($manager) {
			$this->authenticate();
			$url = '%dataAPI%/Manager/json-form/' . $manager;
        	$partial = 'Manager/forms/' . $manager . '.hbs';
			$this->separation->
				app('bundles/Manager/app/forms/any')->
				layout('Manager/forms/any')->
				partial('form', $partial)->
				url('form', $url)->
				template()->
				write($this->response->body);
		});

		$this->slim->get('/Manager/list(/:name)', function ($manager) {
			$this->authenticate();
			$url = '%dataAPI%/json-data/' . $manager . '/all/50/0/{"created_date":-1}';
        	$partial = 'Manager/collections/' . $manager . '.hbs';
			$this->separation->
				app('bundles/Manager/app/collections/any')->
				layout('Manager/collections/any')->
				partial('table', $partial)->
				url('table', $url)->
				template()->
				write($this->response->body);
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
			$managersCacheFile = $this->bundleRoot . '/../managers/cache.json';
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
			$out = [
				['id' => 'a', 'value' => 'Apple'],
				['id' => 'a2', 'value' => 'Appliance'],
				['id' => 'a3', 'value' => 'A Peter Bailey'],
				['id' => 'b', 'value' => 'Bear'],
				['id' => 'c', 'value' => 'Cat'],
				['id' => 'd', 'value' => 'Dog'],
				['id' => 'e', 'value' => 'Elephant'],
				['id' => 'f', 'value' => 'Flowers'],
				['id' => 'g', 'value' => 'Games']
			];
			//foreach ($riders as $rider) {
            //    $out[] = array('id' => $rider['id'], 'value' => $rider['first_name'] . ' ' . $rider['last_name']);
            //}
            echo json_encode($out);
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
				'manager' => $manager,
				'title' => $managerInstance->title,
				'decription' => $managerInstance->decription,
				'acl' => $managerInstance->acl,
				'icon' => $managerInstance->icon,
				'category' => $managerInstance->category
			];
			$managers[] = $record;
			if (method_exists($managerInstance, 'formPartial')) {
				file_put_contents($this->root . '/partials/Manager/forms/' . $manager . '.hbs', $managerInstance->formPartial());
			}
			if (method_exists($managerInstance, 'tablePartial')) {
				file_put_contents($this->root . '/partials/Manager/collections/' . $manager . '.hbs', $managerInstance->tablePartial());
			}
		}
		file_put_contents($managersCacheFile, json_encode(['managers' => $managers], JSON_PRETTY_PRINT));
	}
}