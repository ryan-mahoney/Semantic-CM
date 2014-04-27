<?php
/*
 * @version .4
 * @link https://raw.github.com/virtuecenter/manager/master/available/events_peoples.php
 * @mode upgrade
 *
 */
namespace Manager;

class events_peoples {
	private $field = false;
	public $collection = 'events';
	public $title = 'Peoples';
	public $titleField = 'title';
	public $singular = 'People';
	public $description = '{{count}} people';
	public $definition = 'Coming Soon';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'browser';
	public $category = 'Content';
	public $after = 'function';
	public $function = 'embeddedUpsert';
	public $notice = 'People Saved';
	public $embedded = true;
	public $storage = [
		'collection' => 'events',
		'key' => '_id'
	];

	public function __construct ($field=false) {
		$this->field = $field;
	}

	function first_nameField() {
	    return [
	      'name'    => 'first_name',
	      'placeholder' => 'First Name',
	      'display' => 'InputText',
	      'required'  => true
	    ];
	}

	function last_nameField() {
	    return [
	      'name'    => 'last_name',
	      'placeholder' => 'Last Name',
	      'label'   => 'Last Name',
	      'display' => 'InputText',
	      'required'  => true
	    ];
    }

    function emailField () {
		return [
			'name'		=> 'email',
			'label'		=> 'Email',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function phoneField () {
		return [
			'name'		=> 'phone',
			'label'		=> 'Phone',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function roleField () {
		return [
			'name'		=> 'role',
			'label'		=> 'Role',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function bioField () {
		return [
			'display' => 'Ckeditor',
			'name' => 'bio'
		];
	}

	public function tablePartial () {
		$partial = <<<'HBS'
			{{#EmbeddedCollectionHeader label="Peoples"}}
			{{#if people_sub}}
				<table class="ui table manager segment">
					<thead>
						<tr>
							<th>Title</th>
							<th class="trash">Delete</th>
						</tr>
					</thead>
					<tbody>
						{{#each people_sub}}
							<tr data-id="{{dbURI}}">
								<td>{{first_name}}</td>
								<td>{{role}}</td>
								<td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
							</tr>
						{{/each}}
					</tbody>
				</table>
		    {{else}}
			    {{#EmbeddedCollectionEmpty singular="People"}}
	        {{/if}}
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			{{#EmbeddedHeader}}
	        {{#FieldFull first_name "First Name"}}{{/FieldFull}}
		    {{#FieldFull last_name "Last Name"}}{{/FieldFull}}
		    {{#FieldFull email Email}}{{/FieldFull}}
		    {{#FieldFull phone Phone}}{{/FieldFull}}
		    {{#FieldFull role Role}}{{/FieldFull}}
		    {{#FieldFull bio Bio}}{{/FieldFull}}
		    {{{id}}}
			{{#EmbeddedFooter}}
			<div style="padding-bottom:100px"></div>
HBS;
		return $partial;
	}
}

