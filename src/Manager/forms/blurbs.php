<?php
class blurbs {
	private $field;

	public function __construct ($field) {
		$this->field = $field;
	}

	public $storage = [
		'collection' => 'blurbs',
		'key' => '_id'
	];
	
	function titleField () {
		return [
			'name' => 'title',
			'placeholder' => 'Title',
			'required' => true,
			'display' => $this->field->inputText()
		];
	}

	function bodyField () {
		return [
			'name' => 'body',
			'required' => false,
			'display' => $this->field->ckeditor()		
		];
	}

/*
	function tagsField () {
		return [
			'name' => 'tags',
			'label' => 'Tags',
			'required' => false,
			'transformIn' => function ($data) {
				return $this->field->csvToArray($data);
			},
			'display' => $this->field->inputToTags(),
			'autocomplete' => function () {
				return $this->db->mongoDistinct('blurb', 'tags');
			},
		];
	}
*/
}