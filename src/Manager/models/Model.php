<?php
namespace Opine\Manager;

class Model {
	private $root;
	private $manager;
	private $bundleRoute;
	private $db;
	private $cacheFile;

	public function __construct ($root, $manager, $db, $bundleRoute) {
		$this->root = $root;
		$this->manager = $manager;
		$this->bundleRoute = $bundleRoute;
		$this->db = $db;
		$this->cacheFile = $this->root . '/../cache/managers.json';
	}

    public function cacheWrite (Array $managers) {
        file_put_contents($this->cacheFile, json_encode(['managers' => $managers], JSON_PRETTY_PRINT));
    }

    public function cacheRead () {
        return json_decode(file_get_contents($this->cacheFile), true);
    }

	public function collectionCounts () {
		$countsTemp = $this->db->collection('collection_stats')->find();
        $counts = [];
        foreach ($countsTemp as $count) {
            $counts[$count['collection']] = $count;
        }
        return $counts;
	}

	public function build () {
        $bundles = $this->bundleRoute->bundles(true);
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
            $searcprivate[] = $searchPath;
        }
        $manageprivate = $this->root . $searchPaths[0];
        $managers = [];
        if (!file_exists($this->cacheFile)) {
            @mkdir($managersRoot);
            $this->cacheWrite(['managers' => $managers]);
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
        $this->cacheWrite($managers);
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
}