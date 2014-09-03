<?php
/**
 * Opine\Manager\Application
 *
 * Copyright (c)2013, 2014 Ryan Mahoney, https://github.com/Opine-Org <ryan@virtuecenter.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Opine\Manager;
use Exception;

class Route {
    private $route;
    private $authentication;
    private $separation;
    private $root;
    private $bundleRoute;
    private $formRoute;
    private $form;
    private $search;
    private $manager;
    private $upload;
    private $slugify;
    private $person;
    private $redirect;

    public function __construct ($root, $route, $form, $formRoute, $authentication, $separation, $bundleRoute, $search, $manager, $uploadwrapper, $db, $slugify, $redirect) {
        $this->root = $root;
        $this->route = $route;
        $this->authentication = $authentication;
        $this->separation = $separation;        
        $this->bundleRoute = $bundleRoute;
        $this->search = $search;
        $this->formRoute = $formRoute;
        $this->manager = $manager;
        $this->upload = $uploadwrapper;
        $this->db = $db;
        $this->slugify = $slugify;
        $this->form = $form;
        $this->redirect = $redirect;
    }

    public function paths () {
        //$this->route->get('/Manager', 'managerRoute@dashboard');
        //$this->route->get('/Manager/header', 'managerRoute@header');
        //$this->route->get('/Manager/add/{name}', 'managerRoute@add');
        //$this->route->get('/Manager/edit/{name}/{id}', 'managerRoute@edit');
        //$this->route->get('/Manager/list/{name}', 'managerRoute@listing');
        //$this->route->get('/Manager/login', 'managerRoute@login');
        //$this->route->get('/Manager/logout', 'managerRoute@logout');
        //$this->route->get('/Manager/api/managers', 'managerRoute@apiManagers');
        //$this->route->get('/Manager/api/search', 'managerRoute@apiSearch');
        //$this->route->get('/Manager/data/{manager}', 'managerRoute@collection');
        //$this->route->get('/Manager/data/{manager}/{method}/{limit}', 'managerRoute@collection');
        //$this->route->get('/Manager/data/{manager}/{method}/{limit}/{page}', 'managerRoute@collection');
        //$this->route->get('/Manager/data/{manager}/{method}/{limit}/{page}/{sort}', 'managerRoute@collection');
        //$this->route->get('/Manager/form-json/{manager}', 'managerRoute@form');
        //$this->route->post('/Manager/upload/{manager}/{field}', 'managerRoute@upload');
        //$this->route->post('/Manager/sort/{manager}', 'managerRoute@sort');
        //$this->route->post('/Manager/upsert/{name}', 'managerRoute@upsert');


        $this->route->get('managerRoute@authFilter', '/Manager', [
            '/document/{manager}' => 'managerRoute@add',
            '/document/{manager}/{dbURI}' => 'managerRoute@edit',
            '/collection/{manager}' => 'managerRoute@listing',
            '' => 'managerRoute@dashboard',
            '/header' => 'managerRoute@header',
        ]);

        $this->route->get('/Manager', [
            '/login' => 'managerRoute@login',
            '/logout' => 'managerRoute@logout'
        ]);

        $this->route->get('/Manager/api', [     
            '/managers' => 'managerRoute@apiManagers',
            '/search' => 'managerRoute@apiSearch',
            '/collection/{manager}' => 'managerRoute@collection',
            '/collection/{manager}/{method}'  => 'managerRoute@collection',
            '/collection/{manager}/{method}/{limit}'  => 'managerRoute@collection',
            '/collection/{manager}/{method}/{limit}/{page}'  => 'managerRoute@collection',
            '/collection/{manager}/{method}/{limit}/{page}/{sort}'  => 'managerRoute@collection',
            '/document/{manager}' => 'managerRoute@form'
        ]);

        $this->route->post('managerRoute@authFilter', '/Manager/api', [
            '/upload/{manager}/{field}' => 'managerRoute@upload',
            '/sort/{manager}' => 'managerRoute@sort',
            '/upsert/{manager}' => 'managerRoute@upsert'
        ]);
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

    public function dashboard () {
        $category = '';
        if (isset($_GET['content'])) {
            $category = 'Content';
        }
        if (isset($_GET['reports'])) {
            $category = 'Reports';
        }
        if (isset($_GET['people'])) {
            $category = 'People';
        }
        $this->separation->app('bundles/Manager/app/dashboard')
            ->layout('Manager/dashboard.html')
            ->template()
            ->write();
    }

    public function header () {
        $this->separation->
            app('bundles/Manager/app/header')->
            layout('Manager/header')->
            template()->
            write();
    }

    public function add ($manager) {
        $layout = 'Manager/forms/any';
        if (isset($_GET['embedded'])) {
            $layout = 'Manager/forms/embedded';
        }
        $this->manager->add($manager, $layout);        
    }

    public function edit ($manager, $id) {
        $layout = 'Manager/forms/any';
        if (isset($_GET['embedded'])) {
            $layout = 'Manager/forms/embedded';
        }
        $this->manager->edit($manager, $layout, $id);
    }

    public function listing ($manager) {
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
        $this->manager->table($manager, $layout, $url);
    }

    public function login () {
        if (isset($_SESSION['user']['groups']) && is_array($_SESSION['user']['groups']) && !empty($_SESSION['user']['groups'])) {
            $matched = false;
            foreach ($_SESSION['user']['groups'] as $group) {
                if (preg_match('/^manager/', $group) > 0) {
                    $matched = true;
                    break;
                }
            }
            if ($matched === true) {
                header('Location: /Manager');
            }
        }
        $this->separation->app('bundles/Manager/app/forms/login')->layout('Manager/forms/login')->template()->write();
    }

    public function logout () {
        $this->authentication->logout();
        header('Location: /Manager');
        return;        
    }

    public function apiManagers () {
        $countsTemp = $this->db->collection('collection_stats')->find();
        $counts = [];
        foreach ($countsTemp as $count) {
            $counts[$count['collection']] = $count;
        }
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

    public function build () {
        $bundles = $this->bundleRoute->bundles(true, true);
        $namespacesByPath = [
            '/../managers' => 'Manager'
        ];
        $searchPaths = [
            '/../managers'
        ];
        $bundleByPath = [
            '/../managers' => null
        ];
        foreach ($bundles as $bundle) {
            $searchPath = '/../bundles/' . $bundle . '/managers';
            $namespacesByPath[$searchPath] = $bundle . '\Manager';
            $bundleByPath[$searchPath] = $bundle;
            $searchPaths[] = $searchPath;
        }
        $managersRoot = $this->root . $searchPaths[0];
        $managers = [];
        if (!file_exists($this->manager->cacheFile)) {
            @mkdir($managersRoot);
            $this->manager->cacheWrite(['managers' => $managers]);
        }
        foreach ($searchPaths as $searchPath) {
            $managersRoot = $this->root . $searchPath;
            if (!file_exists($managersRoot)) {
                continue;
            }            
            $dirFiles = glob($managersRoot . '/*.php');
            if (!is_array($dirFiles) || empty($dirFiles)) {
                continue;
            }
            foreach ($dirFiles as $managerClassFile) {
                $manager = basename($managerClassFile, '.php');
                $managerClassName = '\\' . $namespacesByPath[$searchPath] . '\\' . $manager;
                if (!class_exists($managerClassName)) {
                    echo 'Problem: Manager build: ', $managerClassName, ': not autoloaded.', "\n\n";
                    continue;
                }
                $managerInstance = new $managerClassName();
                $groups = ['manager', 'manager-' . $managerInstance->category, 'manager-specific-' . $manager];
                $routes = [
                    '/Manager',
                    '/Manager/list/' . $manager,
                    '/Manager/edit/' . $manager . '/' . $managerInstance->collection . ':{id}',
                    '/Manager/add/' . $manager . '/' . $managerInstance->collection
                ];
                $linkPrefix = (($bundleByPath[$searchPath] != null) ? $bundleByPath[$searchPath] . '-' : '');
                $managers[] = [
                    'manager' => $manager,
                    'title' => $managerInstance->title,
                    'singular' => $managerInstance->singular,
                    'titleField' => (property_exists($managerInstance, 'titleField') ? $managerInstance->titleField : ''),
                    'description' => $managerInstance->description,
                    'definition' => $managerInstance->definition,
                    'acl' => $groups,
                    'icon' => $managerInstance->icon,
                    'category' => $managerInstance->category,
                    'embedded' => (property_exists($managerInstance, 'embedded') ? 1 : 0),
                    'tabs' => (property_exists($managerInstance, 'tabs') ? $managerInstance->tabs : []),
                    'sort' => (property_exists($managerInstance, 'sort') ? $managerInstance->sort : '{"created_date":-1}'),
                    'collection' => $managerInstance->collection,
                    'link' => $linkPrefix . $manager,
                    'bundle' => $bundleByPath[$searchPath],
                    'namespace' => $namespacesByPath[$searchPath]
                ];
                if (method_exists($managerInstance, 'formPartial')) {
                    file_put_contents($this->root . '/partials/Manager/forms/' . $linkPrefix . $manager . '.hbs', $managerInstance->formPartial());
                }
                if (method_exists($managerInstance, 'tablePartial')) {
                    file_put_contents($this->root . '/partials/Manager/collections/' . $linkPrefix . $manager . '.hbs', $managerInstance->tablePartial());
                }
                foreach ($groups as $group) {
                    if (!isset($auth[$group])) {
                        $auth[$group] = [
                            'routes' => [], 'login' => '/Manager/login', 'denied' => '/Manager/noaccess'
                        ];
                    }
                    foreach ($routes as $route) {
                        $auth[$group]['routes'][] = $route;
                    }
                }
            }
        }
        $this->manager->cacheWrite($managers);
        //$this->authenticationBuild($auth);
    }

    private function authenticationBuild ($authorizations) {
        $yamlPath = $this->root . '/../acl/manager.yml';
        $buffer = 'groups:' . "\n";
        foreach ($authorizations as $group => $auth) {
            $buffer .= '    ' . $group . ':' . "\n";
            $buffer .= '        routes:' . "\n";
            sort($auth['routes']);
            $auth['routes'] = array_unique($auth['routes']);
            foreach ($auth['routes'] as $route) {
                $buffer .= '            - ' . "'" . $route . "'" . "\n";
            }
            $buffer .= '        redirectLogin: ' . "'" . $auth['login'] . "'\n";
            $buffer .= '        redirectDenied: ' . "'" . $auth['denied'] . "'\n";
        }
        $folder = $this->root . '/../acl';
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        file_put_contents($yamlPath, $buffer);
        echo 'Good: Manager access control built.', "\n";
    }

    public function upgrade () {
        $manifest = (array)json_decode(file_get_contents('https://raw.github.com/Opine-Org/Semantic-CM/master/available/manifest.json'), true);
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

    public function location () {
        return __DIR__;
    }
}

class ManagerNotFoundException extends Exception {}