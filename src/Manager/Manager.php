<?php
namespace Manager;

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