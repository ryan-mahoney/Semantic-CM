<?php
namespace Manager;

class Manager {
	private $separation;
	private $response;

	public function __construct ($separation, $response) {
		$this->separation = $separation;
		$this->response = $response;
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
		if ($url === false) {
			$url = '%dataAPI%/Manager/data/' . $manager . '/all/50/0/{"created_date":-1}';
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