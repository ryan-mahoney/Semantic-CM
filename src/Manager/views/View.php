<?php
namespace Opine\Manager;

class View {
	private $separation;
    private $model;

	public function __construct ($model, $separation) {
		$this->model = $model;
        $this->separation = $separation;
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
        $url = '/Manager/form-json/' . $bundle . $manager;
        $partial = 'Manager/forms/' . $bundle . $manager . '.hbs';
        $this->separation->
            app('../bundles/Manager/app/forms/any')->
            layout($layout)->
            partial('form', $partial)->
            url('form', $url)->
            args('form', ['id' => $id])->
            template()->
            write();
    }

    public function index ($manager, $layout='Manager/collections/any', $url=false) {
        $namespace = '';
        $managers = $this->model->cacheRead();
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
            $url = '/Manager/api/index/' . $manager . '/manager/50/0/' . $sort;
        }
        $partial = 'Manager/indexes/' . $namespace . $manager . '.hbs';
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