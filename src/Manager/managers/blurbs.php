<?php
namespace Manager;

class blurbs {
	public $collection = 'blurbs';
	public $form = 'blurbs';
	public $titleTable = 'Blurbs';
	public $titleCard = 'Blurbs';
	public $decriptionCard = '%count% blurbs';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'someicon';
	public $category = 'Content';

	public function row (&$document) {
		return [
			'title' => $document['title']
		];
	}
}