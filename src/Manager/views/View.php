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

class View {
	private $separation;
    private $model;

	public function __construct ($model, $separation) {
		$this->model = $model;
        $this->separation = $separation;
	}

    private function resolvePaths (&$manager, &$namespace) {
        if (substr_count($manager, '-') == 1) {
            list($namespace, $manager) = explode('-', $manager);
            $namespace .= '-';
        }
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
        $this->separation->app('../bundles/Manager/app/forms/login')->layout('Manager/forms/login')->template()->write();
    }

    public function add ($linkName, $layout='Manager/forms/any') {
        $this->resolvePaths($linkName, $bundle);
        $url = '/Manager/api/document/' . $linkName;
        $partial = 'Manager/forms/' . $linkName . '.hbs';
        $this->separation->
            app('../bundles/Manager/app/forms/any')->
            layout($layout)->
            partial('form', $partial)->
            url('form', $url)->
            template()->
            write();
    }

    public function edit ($linkName, $layout='Manager/app/forms/any', $id) {
        $url = '/Manager/api/document/' . $linkName;
        $partial = 'Manager/forms/' . $linkName . '.hbs';
        $this->separation->
            app('../bundles/Manager/app/forms/any')->
            layout($layout)->
            partial('form', $partial)->
            url('form', $url)->
            args('form', ['id' => $id])->
            template()->
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
        $this->separation->
            app('../bundles/Manager/app/collections/any')->
            layout($layout)->
            partial('table', $partial)->
            url('table', $url)->
            template()->
            write();
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
        $this->separation->app('../bundles/Manager/app/dashboard')
            ->layout('Manager/dashboard.html')
            ->template()
            ->write();
    }
 
    public function header () {
        $this->separation->
            app('../bundles/Manager/app/header')->
            layout('Manager/header')->
            template()->
            write();
    }
}