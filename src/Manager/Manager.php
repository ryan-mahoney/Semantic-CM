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
namespace Opine\Manager;

class Manager {
    private $separation;
    private $root;

    public function __construct ($root, $separation) {
        $this->separation = $separation;
        $this->root = $root;
    }

    public function add ($manager, $layout='Manager/forms/any') {
        $namespace = '';
        $this->resolvePaths($manager, $namespace);
        $url = '%dataAPI%/Manager/form/' . $manager;
        $partial = 'Manager/forms/' . $namespace . $manager . '.hbs';
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
        $this->resolvePaths($manager, $namespace);
        $url = '%dataAPI%/Manager/form-json/' . $manager;
        $partial = 'Manager/forms/' . $namespace . $manager . '.hbs';
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
        $managersCacheFile = $this->root . '/../managers/cache.json';
        $managers = json_decode(file_get_contents($managersCacheFile), true);
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

    private function resolvePaths (&$manager, &$namespace) {
        if (substr_count($manager, '-') == 1) {
            list($namespace, $manager) = explode('-', $manager);
            $namespace .= '-';
        }
    }
}