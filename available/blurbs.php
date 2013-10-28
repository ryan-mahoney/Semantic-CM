<?php
class blurbs {
	public $collection = 'blurbs';
	public $form = 'blurbs';
	public $headers = [];
	public $title = [];
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'someicon.png';

	public function row (&$document) {
		return [
			'title' => $document['title']
		];
	}
}