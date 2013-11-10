<?php
namespace Manager;

class Manager {
	private $separation;

	public function __construct ($separation) {
		$this->separation = $separation;
	}

	public function add ($manager) {
		$url = '%dataAPI%/Manager/json-manager/' . $manager;
    	$partial = 'Manager/forms/' . $manager . '.hbs';
		$this->separation->
			app('bundles/Manager/app/forms/any')->
			layout('Manager/forms/any')->
			partial('form', $partial)->
			url('form', $url)->
			template()->
			write($this->response->body);
	}

	pubilc function edit ($manager, $id) {
		$url = '%dataAPI%/Manager/json-manager/' . $manager;
    	$partial = 'Manager/forms/' . $manager . '.hbs';
		$this->separation->
			app('bundles/Manager/app/forms/any')->
			layout('Manager/forms/any')->
			partial('form', $partial)->
			url('form', $url)->
			args('form', ['id' => $id])->
			template()->
			write($this->response->body);
	}

	public function table ($manager, $layout = 'Manager/collections/any') {
		$url = '%dataAPI%/json-data/' . $manager . '/all/50/0/{"created_date":-1}';
    	$partial = 'Manager/collections/' . $manager . '.hbs';
		$this->separation->
			app('bundles/Manager/app/collections/any')->
			layout($layout)->
			partial('table', $partial)->
			url('table', $url)->
			template()->
			write($this->response->body);
	}
}