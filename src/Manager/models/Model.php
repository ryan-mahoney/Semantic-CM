<?php
/**
 * Opine\Mangager\Model
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

class Model {
	private $root;
	private $manager;
	private $bundleRoute;
	private $db;
	private $cacheFile;
    private $collectionModel;

	public function __construct ($root, $manager, $db, $bundleRoute, $collectionModel) {
		$this->root = $root;
		$this->manager = $manager;
		$this->bundleRoute = $bundleRoute;
		$this->db = $db;
        $this->collectionModel = $collectionModel;
		$this->cacheFile = $this->root . '/../cache/managers.json';
	}

    public function cacheWrite (Array $managers) {
        file_put_contents($this->cacheFile, json_encode(['managers' => $managers], JSON_PRETTY_PRINT));
    }

    public function cacheRead () {
        return json_decode(file_get_contents($this->cacheFile), true);
    }

    public function managerGetByLink ($linkName) {
        $managers = $this->cacheRead();
        foreach ($managers['managers'] as $manager) {
            if ($manager['link'] == $linkName) {
                return $manager;
            }
        }
        throw new Exception('can not get manager by link: ' . $linkName);
    }

    public function collectionGetByCollection ($collectionName) {
        $collections = $this->collectionModel->cacheRead();
        foreach ($collections as $collectionsData) {
            if ($collectionName == $collectionsData['collection']) {
                return $collectionsData;
            }
        }
        if ($collection === false) {
            throw new Exception('Collection not found: ' . $class);
        }
        return $collection;
    }

    public function collectionGetByClass ($class) {
        $collections = $this->collectionModel->cacheRead();
        foreach ($collections as $collectionsData) {
            if ($class == $collectionsData['class']) {
                return $collectionsData;
            }
        }
        if ($collection === false) {
            throw new Exception('Collection not found: ' . $class);
        }
        return $collection;
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
        $bundles = $this->bundleRoute->bundles();
        $namespacesByPath = [
            '/../managers' => ''
        ];
        $searchPaths = [
            '/../managers'
        ];
        $bundleByPath = [
            '/../managers' => null
        ];
        foreach ($bundles as $bundle) {
            $searchPath = '/../bundles/' . $bundle['name'] . '/managers';
            $namespacesByPath[$searchPath] = $bundle['name'] . '\Manager';
            $bundleByPath[$searchPath] = $bundle['name'];
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
                $managerClassName = (($namespacesByPath[$searchPath] != '') ? $namespacesByPath[$searchPath] . '\\' : '') . '\Manager\\' . $manager;
                if (!class_exists($managerClassName)) {
                    echo 'Problem: Manager build: ', $managerClassName, ': not autoloaded.', "\n\n";
                    continue;
                }
                $managerInstance = new $managerClassName();
                $groups = ['manager', 'manager-' . $managerInstance->category, 'manager-specific-' . $manager];
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
                    'namespace' => $namespacesByPath[$searchPath],
                    'class' => (($namespacesByPath[$searchPath] != '') ? $namespacesByPath[$searchPath] . '\\' : '') . 'Manager\\' . $manager
                ];
                if (method_exists($managerInstance, 'formPartial')) {
                    $dst = $this->root . '/partials/Manager/forms/' . $linkPrefix . $manager . '.hbs';
                    if (!file_exists(dirname($dst))) {
                        @mkdir(dirname($dst), 0777, true);
                    }
                    file_put_contents($dst, $managerInstance->formPartial());
                }
                if (method_exists($managerInstance, 'indexPartial')) {
                    $dst = $this->root . '/partials/Manager/indexes/' . $linkPrefix . $manager . '.hbs';
                    if (!file_exists(dirname($dst))) {
                        @mkdir(dirname($dst), 0777, true);
                    }
                    file_put_contents($dst, $managerInstance->indexPartial());
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