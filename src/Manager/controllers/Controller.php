<?php
namespace Opine\Manager;

class Controller {
	private $model;
	private $view;
	private $service;
	private $authentication;
	private $form;
	private $search;
	private $upload;
    private $slugify;
    private $redirect;

	public function __construct ($model, $view, $service, $authentication, $form, $search, $upload, $slugify, $redirect) {
		$this->model = $model;
		$this->view = $view;
		$this->service = $service;
		$this->authentication = $authentication;
        $this->form = $form;
        $this->search = $search;
        $this->upload = $upload;
        $this->slugify = $slugify;
        $this->redirect = $redirect;
	}

	public function authFilter () {
        if (!isset($_SERVER['REQUEST_URI'])) {
            return false;
        }
        $parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $manager = false;
        if (isset($parts[2])) {
            $manager = $parts[2];
        }

        //check if logged in
        if ($this->authentication->check() === false) {
            return $this->redirect->to('/Manager/login');
        } else {
            if ($manager == false) {
                //check any manager zone
                return true;
            }
        }

        //check if access to this manager
        

        return false;
    }

    public function add ($manager) {
        $layout = 'Manager/forms/any';
        if (isset($_GET['embedded'])) {
            $layout = 'Manager/forms/embedded';
        }
        $this->view->add($manager, $layout);        
    }

    public function edit ($manager, $id) {
        $layout = 'Manager/forms/any';
        if (isset($_GET['embedded'])) {
            $layout = 'Manager/forms/embedded';
        }
        $this->view->edit($manager, $layout, $id);
    }

    public function index ($manager) {
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
        } elseif (isset($_GET['naked']) && isset($_GET['embedded']) && $_GET['embedded'] == 1) {
            $layout = 'Manager/collections/embedded';
        } elseif (isset($_GET['naked'])) {
            $layout = 'Manager/collections/naked';
        }
        $this->view->index($manager, $layout, $url);
    }

    public function logout () {
        $this->authentication->logout();
        header('Location: /Manager');
        return;        
    }

    public function apiManagers () {
        $counts = $this->model->collectionCounts();
        $managers = $this->manager->cacheRead();
        $managersOut = [];
        foreach ($managers['managers'] as $manager) {
            if ($manager['embedded'] == 1) {
                continue;
            }
            if (!empty($_GET['category'])) {
                if ($manager['category'] != $_GET['category']) {
                    continue;
                }
            }
            if (isset($manager['collection']) && isset($counts[$manager['collection']])) {
                $manager['count'] = $counts[$manager['collection']]['count'];
                if (isset ($counts[$manager['collection']]['modified_date'])) {
                    $manager['modified_date'] = $counts[$manager['collection']]['modified_date'];
                }
            } else {
                $manager['count'] = 0;
            }
            //access control
            if (!isset($_SESSION['user']) || !isset($_SESSION['user']['groups']) || empty($_SESSION['user']['groups'])) {
                continue;
            }
            $groups = ['manager', 'manager-' . $manager['category'], 'manager-specific-' . $manager['manager']];
            $matched = false;
            foreach ($groups as $group) {
                if (in_array($group, $_SESSION['user']['groups'])) {
                    $matched = true;
                    break;
                }
            }
            if ($matched === false) {
                continue;
            }
            $managersOut[] = $manager;
        }
        echo json_encode(['managers' => $managersOut], JSON_PRETTY_PRINT);
    }

    public function apiSearch () {
        if (!isset($_GET['q']) || empty($_GET['q']) || !is_string($_GET['q'])) {
            echo json_encode([]);
            return;
        }
        $out = [];
        $matches = $this->search->search('*' . trim(urldecode($_GET['q'])) . '*');
        if (!isset($matches['hits']) || empty($matches['hits']) || !isset($matches['hits']['hits']) || empty($matches['hits']['hits']) || !is_array($matches['hits']['hits'])) {
            echo json_encode([]);
            return;
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
    }

    public function collection ($managerName, $method, $limit=50, $page=1, $sort='{}') {
        $manager = false;
        $managers = $this->manager->cacheRead();
        foreach ($managers['managers'] as $managersData) {
            if ($managersData['manager'] == $managerName) {
                $manager = $managersData;
                break;
            }
        }
        $collectionJson = $this->route->run('GET', '/json-data/' . $managerName . '/' . $method . '/' . $limit . '/' . $page . '/' . $sort);
        if ($manager !== false) {
            $collectionJson = json_decode($collectionJson, true);
            $collectionJson['metadata'] = array_merge($collectionJson['metadata'], $manager);
            $collectionJson = json_encode($collectionJson);
        }
        echo $collectionJson;        
    }

    public function upsert ($linkName) {
        $manager = false;
        $managers = $this->manager->cacheRead();
        foreach ($managers['managers'] as $metadata) {
            if ($metadata['link'] == $linkName) {
                $manager = '\\' . $metadata['namespace'] . '\\' . $metadata['manager'];
                break;
            }
        }
        if ($manager == false) {
            throw new ManagerNotFoundException('Can not find manager with link: ' . $linkName);
        }
        $formObject = $this->form->factory(new $manager);
        $formObject->topicSaved = 'ManagerSaved';
        $formObject->topicSave = 'ManagerSave';
        $formObject->topicDeleted = 'ManagerDeleted';
        $formObject->topicDelete = 'ManagerDelete';
        echo $this->form->upsert($formObject);
    }

    public function form ($linkName) {
        $manager = false;
        $id = false;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $managers = $this->manager->cacheRead();
        foreach ($managers['managers'] as $metadata) {
            if ($metadata['link'] == $linkName) {
                $manager = '\\' . $metadata['namespace'] . '\\' . $metadata['manager'];
                break;
            }
        }
        $formObject = $this->form->factory(new $manager, $id);
        $formJson = $this->form->json($formObject);
        if ($manager !== false) {
            $formJson = json_decode($formJson, true);
            $formJson['metadata'] = $metadata;
            $formJson = json_encode($formJson);
        }
        echo $formJson;
    }

    public function upload ($manager, $field) {
        if (!isset($_FILES)) {
            return;
        }
        $cleanName = $this->slugify->slugify(pathinfo($_FILES[$field]['name'])['filename']);
        $path = '/storage/' . date('Y-m-d-H');
        $storage = $this->upload->storage($path);
        $upload = $this->upload->file($field, $storage);
        $upload->setName($cleanName);
        if ($manager == 'redactor') {
            $data = [
                'filelink'   => $path . '/' . $upload->getNameWithExtension()
            ];
        } else {
            $data = [
                'name' => $upload->getNameWithExtension(),
                'type' => $upload->getMimetype(),
                'size' => $upload->getSize(),
                'md5'  => $upload->getMd5(),
                'width' => $upload->getDimensions()['width'],
                'height' => $upload->getDimensions()['height'],
                'url'   => 'http://' . $_SERVER['HTTP_HOST'] . $path . '/' . $upload->getNameWithExtension()
            ];
        }
        try {
            $result = $upload->upload();
        } catch (\Exception $e) {
            var_dump($upload->getErrors());
            return;
        }
        echo json_encode($data, JSON_PRETTY_PRINT);        
    }

    public function sort ($manager) {
        if (!isset($_POST['sorted']) || !is_array($_POST['sorted']) || count($_POST['sorted']) == 0) {
            echo json_encode(['success' => true]);
            return;
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
            return;
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
                return;
            } elseif ($depth == 5) {
                $embedded = $parts[($depth - 3)];
                $embeddedId = $parts[($depth - 2)];
                $embedded = $parts[($depth - 1)];
            } else {
                echo 'Wow!  That is a deep sort!';
            }
        }
    }
}