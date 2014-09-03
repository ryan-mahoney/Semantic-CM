<?php
/**
 * Opine\Mangager\Manager
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
namespace Opine;

class Manager {
    private $separation;
    private $root;
    private $post;
    private $authentication;
    private $db;
    private $collection;
    public $cacheFile;

    public function __construct ($root, $separation, $post, $authentication, $db, $collection) {
        $this->separation = $separation;
        $this->root = $root;
        $this->post = $post;
        $this->authentication = $authentication;
        $this->db = $db;
        $this->collection = $collection;
        $this->cacheFile = $this->root . '/../cache/managers.json';
    }

    public function cacheWrite (Array $managers) {
        file_put_contents($this->cacheFile, json_encode(['managers' => $managers], JSON_PRETTY_PRINT));
    }

    public function cacheRead () {
        return json_decode(file_get_contents($this->cacheFile), true);
    }

    public function add ($manager, $layout='Manager/forms/any') {
        $namespace = '';
        $this->resolvePaths($manager, $bundle);
        $url = '%dataAPI%/Manager/form-json/' . $manager;
        $partial = 'Manager/forms/' . $bundle . $manager . '.hbs';
        $this->separation->
            app('bundles/Manager/app/forms/any')->
            layout($layout)->
            partial('form', $partial)->
            url('form', $url)->
            template()->
            write();
    }

    public function edit ($manager, $layout='Manager/app/forms/any', $id) {
        $namespace = '';
        $this->resolvePaths($manager, $bundle);
        $url = '%dataAPI%/Manager/form-json/' . $bundle . $manager;
        $partial = 'Manager/forms/' . $bundle . $manager . '.hbs';
        $this->separation->
            app('bundles/Manager/app/forms/any')->
            layout($layout)->
            partial('form', $partial)->
            url('form', $url)->
            args('form', ['id' => $id])->
            template()->
            write();
    }

    public function table ($manager, $layout='Manager/collections/any', $url=false) {
        $namespace = '';
        $this->resolvePaths($manager, $namespace);        
        $managers = $this->cacheRead();
        foreach ($managers['managers'] as $managerCache) {
            if ($managerCache['manager'] == $manager) {
                break;
            }
        }
        $sort = '{"created_date":-1}';
        if (isset($managerCache['sort'])) {
            $sort = $managerCache['sort'];
        }
        if ($url === false) {
            $url = '%dataAPI%/Manager/data/' . $manager . '/manager/50/0/' . $sort;
        }
        $partial = 'Manager/collections/' . $namespace . $manager . '.hbs';
        $this->separation->
            app('bundles/Manager/app/collections/any')->
            layout($layout)->
            partial('table', $partial)->
            url('table', $url)->
            template()->
            write();
    }

    public function post ($context) {
        if (!isset($context['dbURI']) || empty($context['dbURI'])) {
            throw new \Exception('Context does not contain a dbURI');
        }
        if (!isset($context['formMarker'])) {
            throw new \Exception('Form marker not set in post');
        }
        $document = $this->post->{$context['formMarker']};
        if ($document === false || empty($document)) {
            throw new \Exception('Document not found in post');
        }
        $documentInstance = $this->db->documentStage($context['dbURI'], $document);
        $documentInstance->upsert();
        $this->post->statusSaved();
        $document = $documentInstance->current();
        $id = $documentInstance->id();
        $collectionName = $documentInstance->collection();
        $collectionClass = '\Collection\\' . $this->collection->toCamelCase($collectionName);
        $collectionInstance = $this->collection->factory(new $collectionClass());
        if ($collectionInstance === false) {
            return;
        }
        $collectionInstance->index($id, $document);
        $collectionInstance->views('upsert', $id, $document);
        $collectionInstance->statsUpdate($context['dbURI']);
    }

    private function resolvePaths (&$manager, &$namespace) {
        if (substr_count($manager, '-') == 1) {
            list($namespace, $manager) = explode('-', $manager);
            $namespace .= '-';
        }
    }

    public function authenticate ($context) {
        if (!isset($context['dbURI']) || empty($context['dbURI'])) {
            throw new \Exception('Context does not contain a dbURI');
        }
        if (!isset($context['formMarker'])) {
            throw new \Exception('Form marker not set in post');
        }
        $document = $this->post->{$context['formMarker']};
        if ($document === false || empty($document)) {
            throw new \Exception('Document not found in post');
        }
        if (!isset($document['route'])) {
            $this->post->errorFieldSet($context['formMarker'], 'Missing url.');
            return;
        }
        $try = $this->authentication->login($document['email'], $document['password']);
        if ($try === false) {
            $this->post->errorFieldSet($context['formMarker'], 'Credentials do not match. Please check your email or password and try again.');
            return;    
        }
        if (!$this->authentication->checkRoute($document['route'], false)) {
            $this->post->errorFieldSet($context['formMarker'], 'You do not have access to the area.');
            return;
        }
        $this->post->statusSaved();
    }
}