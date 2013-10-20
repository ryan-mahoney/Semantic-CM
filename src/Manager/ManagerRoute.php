<?php
namespace Manager;

class ManagerRoute {
	private $slim;
	private $authentication;
	

	public function __construct ($authentication, $separation, $slim, $form, $manager) {
		//if (!$authentication->valid()) {
		//	header('Location: /manager/login');
		//	exit;
		//}
		$this->slim = $slim;
	}

	public function app ($root) {
		$cacheFile = $root . '/manager/cache.json';
		if (!file_exists($cacheFile)) {
			return;
		}
		$managers = (array)json_decode(file_get_contents($cacheFile), true);
		if (!is_array($managers)) {
			return;
		}
	    foreach ($managers as $manager) {
	    	$this->slim->get('/manager/' . $manager, function () use ($manager) {
                $this->separation->layout('manager-table')->template()->write($this->response->body);
            })->name('manager ' . $manager);
	    	
	    	$this->slim->get('/manager/form/' . $manager . '(/:id)', function ($id=false) use ($manager) {
                if ($id === false) {
                	$this->separation->layout('manager-form')->template()->write($this->response->body);
                } else {
                	$this->separation->layout('manager-form')->set([
                		['id' => $manager, 'args' => ['id' => $id]]
                	])->template()->write($this->response->body);
                }
            });
            
            $this->slim->post('/manager/form/' . $manager . '(/:id)', function ($id=false) use ($manager) {
            	$formObject = $this->form->factory($manager, $id);
            	if ($id === false) {
            		if (isset($this->post->{$$formObject->marker}['id'])) {
            			$id = $this->post->{$$formObject->marker}['id'];
            		} else {
            			throw new \Exception('ID not supplied in post.');
            		}
            	}
               	$event = [
            		'dbURI' => $formObject->storage['collection'] . ':' . $id,
            		'formMarker' => $formObject->marker
            	];
            	if (!$this->form->validate($formObject)) {
            		$this->form->responseError();
            		return;
            	}
            	$this->form->sanitize($formObject);
            	$this->topic->publish('form-' . $manager . '-manager-save', $event);
            	if ($this->post->statusCheck() == 'saved') {
            		$this->form->responseSuccess($managerObject);
            	} else {
            		$this->form->responseError();	
            	}
            });
	    }
	}
}