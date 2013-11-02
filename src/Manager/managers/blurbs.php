<?php
namespace Manager;

class blurbs {
	public $collection = 'blurbs';
	public $form = 'blurbs';
	public $titleTable = 'Blurbs';
	public $titleCard = 'Blurbs';
	public $decriptionCard = '5 blurbs';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'basic content';
	public $category = 'Content';

	public function row (&$document) {
		return [
			'title' => $document['title']
		];
	}
}