<?php
/**
 * Opine\Mangager\Controller
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

class Controller {
    private $model;
    private $view;
    private $manager;
    private $authentication;
    private $form;
    private $search;
    private $upload;
    private $slugify;
    private $redirect;
    private $route;

    public function __construct ($model, $view, $service, $authentication, $form, $search, $upload, $slugify, $route, $redirect) {
        $this->model = $model;
        $this->view = $view;
        $this->manager = $service;
        $this->authentication = $authentication;
        $this->form = $form;
        $this->search = $search;
        $this->upload = $upload;
        $this->slugify = $slugify;
        $this->route = $route;
        $this->redirect = $redirect;
    }

    public function login () {
        $this->view->login();
    }

    public function authFilter () {
        return true;
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

    public function edit ($linkName, $id) {
        $layout = 'Manager/forms/any';
        if (isset($_GET['embedded'])) {
            $layout = 'Manager/forms/embedded';
        }
        $this->view->edit($linkName, $layout, $id);
    }

    public function index ($linkName) {
        $layout = 'Manager/collections/any';
        $url = false;
        if (isset($_GET['embedded']) && isset($_GET['dbURI'])) {
            $parts = explode(':', $_GET['dbURI']);
            $collection = $parts[0];
            $collectionData = $this->model->collectionGetByCollection($collection);
            if ((count($parts) % 2) == 0) {
                array_pop($parts);
            }
            $layout = 'Manager/collections/embedded';
            $url = (($collectionData['namespace'] != '') ? '/' . $collectionData['namespace'] : '') . '/api/collection/' . $collectionData['name'] . '/byEmbeddedField-' . implode(':', $parts);
        } elseif (isset($_GET['naked']) && isset($_GET['embedded']) && $_GET['embedded'] == 1) {
            $layout = 'Manager/collections/embedded';
        } elseif (isset($_GET['naked'])) {
            $layout = 'Manager/collections/naked';
        }
        $this->view->index($linkName, $layout, $url);
    }

    public function indexEmbedded ($linkName, $field, $dbURI) {
        $parts = explode(':', $dbURI);
        $collection = $parts[0];
        $collectionData = $this->model->collectionGetByCollection($collection);
        if ((count($parts) % 2) == 0) {
            array_pop($parts);
        }
        $layout = 'Manager/collections/embedded';
        $url = (($collectionData['namespace'] != '') ? '/' . $collectionData['namespace'] : '') . '/api/collection/' . $collectionData['name'] . '/byEmbeddedField-' . $dbURI . ':' . $field;
        $this->view->index($linkName, $layout, $url);
    }

    public function logout () {
        $this->authentication->logout();
        header('Location: /Manager');
        return;        
    }

    public function header () {
        $this->view->header();
    }

    public function dashboard () {
        $this->view->dashboard();
    }
}