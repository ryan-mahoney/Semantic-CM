<?php
/*
 * @version .2
 * @link https://raw.github.com/virtuecenter/manager/master/available/menus.php
 * @mode upgrade
 */
namespace Manager;

class menus {
	private $field = false;
	public $collection = 'menus';
	public $form = 'menus';
	public $title = 'Menus';
	public $singular = 'Menu';
	public $description = '4 menu items';
	public $definition = 'Menus are used for the navigation of your public website.';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'browser';
	public $category = 'Content';
	public $notice = 'Menu Saved';
	public $storage = [
		'collection' => 'menus',
		'key' => '_id'
	];

	public function __construct ($field=false) {
		$this->field = $field;
	}

	function labelField () {
		return [
			'name'		=> 'label',
			'placeholder'		=> 'Label',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}	

	function urlField () {
		return [
			'name'		=> 'url',
			'placeholder'		=> 'URL',
			'required'	=> false,
			'display'	=> 'InputText'
		];
	}

/*
	function imageField () {
		return [
			'name' => 'file',
			'placeholder' => 'Image',
			'display' => VCPF\Field::inputFile()
		];
	}
*/

	public function linkField() {
		return [
			'name' => 'link',
			'required' => false,
			'display'	=>	'Manager',
			'manager'	=> 'menu_links'
		];
	}

	public function tablePartial () {
		$partial = <<<'HBS'
			<div class="top-container">
				{{#CollectionHeader}}{{/CollectionHeader}}
			</div>
			<div class="bottom-container">
				{{#CollectionPagination}}{{/CollectionPagination}}
				{{#CollectionButtons}}{{/CollectionButtons}}
				
				<table class="ui large table segment manager">
			  		<thead>
						<tr>
							<th>Title</th>
							<th class="trash">Delete</th>
						</tr>
			  		</thead>
			   		<tbody>
			   			{{#each menus}}
							<tr data-id="{{dbURI}}">
								<td>{{label}}</td>
								<td>
									<div class="manager trash ui icon button">
	                 					<i class="trash icon"></i>
	                 				</div>
	             				</td>
							</tr>
						{{/each}}
					</tbody>
				</table>

				{{#CollectionPagination}}{{/CollectionPagination}}
				{{#CollectionButtons}}{{/CollectionButtons}}
			</div>
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			<div class="top-container">
				{{#DocumentHeader}}{{/DocumentHeader}}
		        {{#DocumentTabsButtons}}{{/DocumentTabsButtons}}
		    </div>
		    {{#DocumentFormLeft}}			
         		<div class="ui form">
                    <div class="field"required>
                       	<label>Label</label>
						<div class="ui left labeled input">
							{{{label}}}
							<div class="ui corner label">
								<i class="icon asterisk"></i>
							</div>
						</div>
                    </div>
                </div>
                <div class="ui form">
                    <div class="field">
                        <label>URL</label>
						<div class="ui left labeled input">
							{{{url}}}
							<div class="ui corner label">
								<i class="icon asterisk"></i>
							</div>
						</div>
                    </div>
                </div>
                <div class="field embedded" data-field="link" data-manager="menu_links" style="width: 96%; margin-left: 2%">
					{{{link}}}
				</div>
				{{{id}}}
			{{/DocumentFormLeft}}             	
			{{#DocumentFormRight}}{{/DocumentFormRight}}
HBS;
		return $partial;
	}
}