<?php
/**
 * Opine\Mangager\ApiController
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

class ApiController {
    private $model;
    private $form;
    private $search;
    private $upload;
    private $slugify;
    private $route;

    public function __construct ($model, $form, $search, $upload, $slugify, $route) {
        $this->model = $model;
        $this->form = $form;
        $this->search = $search;
        $this->upload = $upload;
        $this->slugify = $slugify;
        $this->route = $route;
    }

    public function managers () {
        $counts = $this->model->collectionCounts();
        $managers = $this->model->cacheRead();
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
            if (isset($manager['collection_']) && isset($counts[$manager['collection_']])) {
                $manager['count'] = $counts[$manager['collection_']]['count'];
                if (isset ($counts[$manager['collection_']]['modified_date'])) {
                    $manager['modified_date'] = $counts[$manager['collection_']]['modified_date'];
                }
            } else {
                $manager['count'] = 0;
            }
            if (!$this->model->authManagerCheck($manager)) {
                continue;
            }
            $managersOut[] = $manager;
        }
        echo json_encode(['managers' => $managersOut], JSON_PRETTY_PRINT);
    }

    public function search () {
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
                'type' => ucwords(str_replace('_', ' ', $hit['_source']['collection'])),
                'value' => $hit['_source']['title']
            ];
        }
        echo json_encode($out);
    }

    public function collection ($linkName, $method='manager', $limit=50, $page=1, $sort='{}') {
        $manager = $this->model->managerGetByLink($linkName);
        $collection = $this->model->collectionGetByClass($manager['collection']);
        $collectionPath = '';
        if ($collection['bundle'] != '') {
            $collectionPath .= '/' . $collection['bundle'];
        }
        $collectionPath .= '/api/collection/' . $collection['name'];
        $collectionJson = $this->route->run('GET', $collectionPath . '/' . $method . '/' . $limit . '/' . $page . '/' . $sort);
        if ($manager !== false) {
            $collectionJson = json_decode($collectionJson, true);
            $collectionJson['metadata'] = array_merge($collectionJson['metadata'], $manager);
            $collectionJson = json_encode($collectionJson);
        }
        echo $collectionJson;
    }

    public function upsert ($linkName) {
        $manager = $this->model->managerGetByLink($linkName);
        $class = $manager['class'];
        $formObject = $this->form->factory(new $class);
        $formObject->manager = $manager;
        $formObject->topicSaved = 'ManagerSaved';
        $formObject->topicSave = 'ManagerSave';
        $formObject->topicDeleted = 'ManagerDeleted';
        $formObject->topicDelete = 'ManagerDelete';
        echo $this->form->upsert($formObject);
    }

    public function form ($linkName) {
        $id = false;
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $manager = $this->model->managerGetByLink($linkName);
        $class = $manager['class'];
        $formObject = $this->form->factory(new $class, $id);
        $formJson = $this->form->json($formObject);
        $formJson = json_decode($formJson, true);
        $formJson['metadata'] = $manager;
        $formJson = json_encode($formJson);
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
        } catch (Exception $e) {
            var_dump($upload->getErrors());
            return;
        }
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function sort () {
        if (!isset($_POST['sorted']) || !is_array($_POST['sorted']) || count($_POST['sorted']) == 0) {
            echo json_encode(['success' => true]);
            return;
        }
        $this->model->sort($_POST);
    }

    public function delete ($dbURI) {
        $result = $this->model->delete($dbURI);
        echo json_encode(['success' => $result]);
    }
}