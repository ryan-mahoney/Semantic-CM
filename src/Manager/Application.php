<?php
namespace Manager;

class Application {
	private $slim;
	private $authentication;
	private $separation;
	private $response;
	private $root;
	private $bundleRoot;
	private $formRoute;
	private $search;
	private $manager;
	private $upload;
	private $slugify;

	public function __construct ($container, $root, $bundleRoot) {
		$this->slim = $container->slim;
		$this->authentication = $container->authentication;
		$this->separation = $container->separation;
		$this->response = $container->response;
		$this->root = $root;
		$this->bundleRoot = $bundleRoot;
		$this->search = $container->search;
		$this->formRoute = $container->formRoute;
		$this->manager = $container->manager;
		$this->upload = $container->uploadwrapper;
		$this->db = $container->db;
	}

	public function app () {
		$this->formRoute->app($this->root, '', 'managers', 'Manager\\', 'manager', '/Manager');
		$this->formRoute->json('', 'managers', 'Manager\\', 'manager', '/Manager');

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
			$layout = 'Manager/forms/any';
			if (isset($_GET['embedded'])) {
				$layout = 'Manager/forms/embedded';
			}
			$this->authenticate();
			$this->manager->add($manager, $layout, $this->response->body);
		});

		$this->slim->get('/Manager/edit(/:name(/:id))', function ($manager, $id) {
			$layout = 'Manager/forms/any';
			if (isset($_GET['embedded'])) {
				$layout = 'Manager/forms/embedded';
			}
			$this->authenticate();
			$this->manager->edit($manager, $layout, $id);
		});

		$this->slim->get('/Manager/list(/:name)', function ($manager) {
			$layout = 'Manager/collections/any';
			$url = false;
			if (isset($_GET['embedded']) && isset($_GET['dbURI'])) {
				$parts = explode(':', $_GET['dbURI']);
				$collection = $parts[0];
				if ((count($parts) % 2) == 0) {
					array_pop($parts);
				}
				$layout = 'Manager/collections/embedded';
				$url = '%dataAPI%/json-data/' . $collection . '/byEmbeddedField-' . implode(':', $parts);
			} elseif (isset($_GET['naked'])) {
				$layout = 'Manager/collections/embedded';
			}
			$this->manager->table($manager, $layout, $this->response->body, $url);
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
			$managers = json_decode(file_get_contents($managersCacheFile), true);
			$managersOut = [];
			foreach ($managers['managers'] as $manager) {
				if ($manager['embedded'] == 1) {
					continue;
				}
				$managersOut[] = $manager;
			}
			if ($userId===false) {
				$this->response->body = json_encode(['managers' => $managersOut]);
			}
			//get user's acl for manager
			//filter out managers he user does not have access to
		});

		$this->slim->get('/Manager/api/search', function () {
			$this->authenticate();
			if (!isset($_GET['q']) || empty($_GET['q']) || !is_string($_GET['q'])) {
				echo json_encode([]);
				exit;
			}
			$out = [];
			$matches = $this->search->search('*' . trim(urldecode($_GET['q'])) . '*');
			if (!isset($matches['hits']) || empty($matches['hits']) || !isset($matches['hits']['hits']) || empty($matches['hits']['hits']) || !is_array($matches['hits']['hits'])) {
				echo json_encode([]);
				exit;
			}
			foreach ($matches['hits']['hits'] as $hit) {
				if (!isset($hit['_source']['url_manager']) || empty($hit['_source']['url_manager'])) {
					continue;
				}
				$out[] = [
					'id' => $hit['_source']['url_manager'],
					'type' => ucwords(str_replace('_', ' ', $hit['_type'])),
					'value' => $hit['_source']['title']
				];
			}
            echo json_encode($out);
		});

		$this->slim->get('/Manager/data/:path+', function ($path) {
			$manager = false;
			$name = $path[0];
			$path = implode('/', $path);
			$managers = (array)json_decode(file_get_contents($this->root . '/../managers/cache.json'), true);
			foreach ($managers['managers'] as $managersData) {
				if ($managersData['manager'] == $name) {
					$manager = $managersData;
					break;
				}
			}
			$collectionJson = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/json-data/' . $path);
			if ($manager !== false) {
				$collectionJson = json_decode($collectionJson, true);
				$collectionJson['metadata'] = array_merge($collectionJson['metadata'], $manager);
				$collectionJson = json_encode($collectionJson);
			}
			echo $collectionJson;
		});

		$this->slim->get('/Manager/form/:path+', function ($path) {
			$manager = false;
			$name = $path[0];
			$path = implode('/', $path);
			$id = '';
			if (isset($_GET['id'])) {
				$id = '/' . $_GET['id'];
			}
			$managers = (array)json_decode(file_get_contents($this->root . '/../managers/cache.json'), true);
			foreach ($managers['managers'] as $managersData) {
				if ($managersData['manager'] == $name) {
					$manager = $managersData;
					break;
				}
			}
			$formJson = file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/Manager/json-manager/' . $path . $id);
			if ($manager !== false) {
				$formJson = json_decode($formJson, true);
				$formJson['metadata'] = $manager;
				$formJson = json_encode($formJson);
			}
			echo $formJson;
		});

		$this->slim->post('/Manager/upload/:manager/:field', function ($manager, $field) {
			$this->authenticate();
			if (!isset($_FILES)) {
				return;
			}
			$cleanName = $this->slugify->slugify(pathinfo($_FILES[$field]['name'])['filename']);
			$path = '/storage/' . date('Y-m-d-H');
			$storage = $this->upload->storage($path);
			$upload = $this->upload->file($field, $storage);
			$upload->setName($cleanName);
			$data = [
			    'name' => $upload->getNameWithExtension(),
			    'type' => $upload->getMimetype(),
			    'size' => $upload->getSize(),
			    'md5' => $upload->getMd5(),
			    'width' => $upload->getDimensions()['width'],
			    'height' => $upload->getDimensions()['height'],
			    'url'	=> 'http://' . $_SERVER['HTTP_HOST'] . $path . '/' . $upload->getNameWithExtension()
			];
			try {
    			$result = $upload->upload();
			} catch (\Exception $e) {
    		    var_dump($upload->getErrors());
    		    exit;
			}
			echo json_encode($data, JSON_PRETTY_PRINT);
			exit;
		});

		$this->slim->post('/Manager/sort/:manager', function ($manager) {
			//echo $manager;
			if (!isset($_POST['sorted']) || !is_array($_POST['sorted']) || count($_POST['sorted']) == 0) {
				echo json_encode(['success' => true]);
				exit;
			}
			$sample = $_POST['sorted'][0];
			$depth = substr_count($sample, ':');
			if ($depth == 1) {
				$offset = 1;
				foreach ($_POST['sorted'] as $dbURI) {
					$parts = explode(':', $dbURI);
					$this->db->collection($parts[0])->update([
							'_id' => $this->db->id($parts[1])
						], [
							'$set' => [
								'sort_key' => $offset
							]
						]);
					$offset++;
				}
				echo json_encode(['success' => true]);
				exit;
			} else {
				$parts = explode(':', $sample);
				$dbURI = $parts[0] . ':' . $parts[1];
				$documentInstance = $this->db->documentStage($dbURI);
				$document = $documentInstance->current();
				if ($depth == 3) {
					$embedded = $parts[($depth - 1)];
					$newDocument = [];
					foreach ($_POST['sorted'] as $dbURIEmbedded) {
						foreach ($document[$embedded] as $embeddedDocument) {
							if ($dbURIEmbedded == $embeddedDocument['dbURI']) {
								$newDocument[] = $embeddedDocument;
								continue;
							}
						}
					}
					$document[$embedded] = $newDocument;
					$this->db->documentStage($dbURI, $document)->upsert();
					echo json_encode(['success' => true]);
					exit;
				} elseif ($depth == 5) {
					$embedded = $parts[($depth - 3)];
					$embeddedId = $parts[($depth - 2)];
					$embedded = $parts[($depth - 1)];
					echo 'Five';
				} else {
					echo 'Wow!  That is a deep sort!';
				}
			}
		});
	}

	private function authenticate () {
		if ($this->authentication->valid('Manager') === false) {
			header('Location: /Manager/login');
			exit;
		}
		return true;
	}

	public function build ($bundleRoot) {
		$managersRoot = $this->root . '/../managers';
		$managersCacheFile = $managersRoot . '/cache.json';
		$managers = [];
		if (!file_exists($managersRoot)) {
			mkdir($managersRoot);
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
				'singular' => $managerInstance->singular,
				'titleField' => (property_exists($managerInstance, 'titleField') ? $managerInstance->titleField : ''),
				'description' => $managerInstance->description,
				'definition' => $managerInstance->definition,
				'acl' => $managerInstance->acl,
				'icon' => $managerInstance->icon,
				'category' => $managerInstance->category,
				'embedded' => (property_exists($managerInstance, 'embedded') ? 1 : 0),
				'tabs' => (property_exists($managerInstance, 'tabs') ? $managerInstance->tabs : []),
				'sort' => (property_exists($managerInstance, 'sort') ? $managerInstance->sort : '{"created_date":1}')
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

	public function upgrade ($bundleRoot) {
        $manifest = (array)json_decode(file_get_contents('https://raw.github.com/virtuecenter/manager/master/available/manifest.json'), true);
        $upgraded = 0;
        foreach (glob($this->root . '/../managers/*.php') as $filename) {
            $lines = file($filename);
            $version = false;
            $mode = false;
            $link = false;
            foreach ($lines as $line) {
                if (substr_count($line, ' * @') != 1) {
                    continue;
                }
                if (substr_count($line, '* @mode') == 1) {
                    $mode = trim(str_replace('* @mode', '', $line));
                    continue;
                }
                if (substr_count($line, '* @version') == 1) {
                    $version = floatval(trim(str_replace('* @version', '', $line)));
                    continue;
                }
                if (substr_count($line, '* @link') == 1) {
                    $link = trim(str_replace('* @link', '', $line));
                    continue;
                }
            }
            if ($mode === false || $version === false || $link === false) {
                continue;
            }
            if ($version == '' || $link == '' || $mode == '') {
                continue;
            }
            if ($mode != 'upgrade') {
                continue;
            }
            if ($version == $manifest['managers'][basename($filename, '.php')]) {
                continue;
            }
            $newVersion = floatval($manifest['managers'][basename($filename, '.php')]);
            if ($newVersion > $version) {
                file_put_contents($filename, file_get_contents($link));
                echo 'Upgraded Manager: ', basename($filename, '.php'), ' to version: ', $newVersion, "\n";
                $upgraded++;
            }
        }
        echo 'Upgraded ', $upgraded, ' managers.', "\n";
    }
}
