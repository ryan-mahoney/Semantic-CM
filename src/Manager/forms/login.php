<?php
namespace Manager\Form;

class login {
	public function __construct ($field) {
		$this->field = $field;
	}

	public $storage = [
		'collection'	=> 'login_attempts',
		'key'			=> '_id'
	];
	public $after = 'redirect';
	public $redirect = '/Manager';

	function emailField() {
		return [
			'name' => 'email',
			'placeholder' => 'Email',
			'display' => $this->field->inputText(),
			'required' => true
		];
	}

	function passwordField() {
		return [
			'name' => 'password',
			'placeholder' => 'Password',
			'display' => $this->field->inputPassword(),
			'required' => true
		];
	}
}