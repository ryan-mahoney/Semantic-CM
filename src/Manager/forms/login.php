<?php
namespace Manager\Form;

class login {
    public function __construct ($field) {
        $this->field = $field;
    }

    public $storage = [
        'collection'    => 'login_attempts',
        'key'            => '_id'
    ];
    public $after = 'redirect';
    public $redirect = '/Manager';

    function emailField() {
        return [
            'name' => 'email',
            'placeholder' => 'Email',
            'display' => 'InputText',
            'required' => true
        ];
    }

    function passwordField() {
        return [
            'name' => 'password',
            'placeholder' => 'Password',
            'display' => 'InputPassword',
            'required' => true
        ];
    }
}