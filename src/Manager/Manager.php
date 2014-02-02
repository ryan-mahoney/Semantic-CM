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
    private $response;

    public function __construct ($separation, $response, $root) {
        $this->separation = $separation;
        $this->response = $response;
        $this->root = $root;
    }

    public function add ($manager, $layout='Manager/forms/any', &$buffer) {
        $url = '%dataAPI%/Manager/form/' . $manager;
        $partial = 'Manager/forms/' . $manager . '.hbs';
        $this->separation->
            app('bundles/Manager/app/forms/any')->
            layout($layout)->
            partial('form', $partial)->
            url('form', $url)->
            template()->
            write($buffer);
    }

    public function edit ($manager, $layout='Manager/app/forms/any', $id, &$buffer=false) {
        $url = '%dataAPI%/Manager/form/' . $manager;
        $partial = 'Manager/forms/' . $manager . '.hbs';
        $this->separation->
            app('bundles/Manager/app/forms/any')->
            layout($layout)->
            partial('form', $partial)->
            url('form', $url)->
            args('form', ['id' => $id])->
            template()->
            write($buffer);
    }

    public function table ($manager, $layout='Manager/collections/any', &$buffer, $url=false) {
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
        $partial = 'Manager/collections/' . $manager . '.hbs';
        $this->separation->
            app('bundles/Manager/app/collections/any')->
            layout($layout)->
            partial('table', $partial)->
            url('table', $url)->
            template()->
            write($buffer);
    }
}