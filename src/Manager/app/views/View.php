<?php
/**
 * Opine\Mangager\View
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

use Opine\Interfaces\Layout as LayoutInterface;

class View {
	private $layout;
    private $model;

	public function __construct ($model, LayoutInterface $layout) {
		$this->model = $model;
        $this->layout = $layout;
	}

    private function resolvePaths (&$manager, &$namespace) {
        if (substr_count($manager, '-') == 1) {
            list($namespace, $manager) = explode('-', $manager);
            $namespace .= '-';
        }
    }

	public function login () {
        $this->layout->config('Manager/forms/login')->container('Manager/forms/login')->write();
    }

    public function add ($linkName, $layout='Manager/forms/any') {
        $this->resolvePaths($linkName, $bundle);
        $url = '/Manager/api/document/' . $linkName;
        $partial = 'Manager/forms/' . $linkName . '.hbs';
        $this->layout->
            config('Manager/forms/any')->
            container($layout)->
            partial('form', $partial)->
            url('form', $url)->
            write();
    }

    public function edit ($linkName, $layout='Manager/app/forms/any', $id) {
        $url = '/Manager/api/document/' . $linkName;
        $partial = 'Manager/forms/' . $linkName . '.hbs';
        $this->layout->
            config('Manager/forms/any')->
            container($layout)->
            partial('form', $partial)->
            url('form', $url)->
            args('form', ['id' => $id])->
            write();
    }

    public function index ($linkName, $layout='Manager/collections/any', $url=false) {
        $manager = $this->model->managerGetByLink($linkName);
        $sort = '{"created_date":-1}';
        if (isset($manager['sort'])) {
            $sort = $manager['sort'];
        }
        if ($url === false) {
            $url = '/Manager/api/index/' . $linkName . '/manager/50/0/' . $sort;
        }
        $partial = 'Manager/indexes/' . $linkName . '.hbs';
        $this->layout->
            config('Manager/collections/any')->
            container($layout)->
            partial('table', $partial)->
            url('table', $url)->
            write();
    }

    public function dashboard ($section) {
        $this->layout->config('Manager/dashboard')->
            container('Manager/dashboard.html')->
            url('managers', '/Manager/api/managers?category=' . $section)->
            write();
    }

    public function header () {
        $this->layout->
            config('Manager/header')->
            container('Manager/header')->
            write();
    }
}