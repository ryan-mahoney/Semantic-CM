<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/menus.php
 * @mode upgrade
 */
namespace Manager;

class menus {
	private $field = false;
	public $collection = 'menus';
	public $form = 'menus';
	public $title = 'Menus';
	public $single = 'Menu';
	public $description = '4 menu items';
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
			<br />
			<h1 class="ui manager header">Menu Items</h1>

<div class="ui borderless pagination menu large fluid">
  <a class="item">
    <i class="icon left arrow"></i> Previous
  </a>
  <a class="item">1</a>
  <a class="item">2</a>
  <a class="item">3</a>
  <a class="item">4</a>
  <a class="item">5</a>
  <a class="item">6</a>
  <a class="item">
    Next <i class="icon right arrow"></i>
  </a>
  <div class="item right">
        <div class="ui teal button large manager add">Add</div>
    </div>
</div>

			<table class="ui table manager segment padded">
				<thead>
					<tr><th>Label</th></tr>
				</thead>
					<tbody>
					{{#each menus}}
						<tr data-id="{{dburi}}">
							<td>{{label}}</td>
						</tr>
					{{/each}}
				</tbody>
			</table>
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			<h2 class="ui manager header">Menu Navigation</h2>
    		<form class="ui form segment" data-xhr="true" method="post" action="/Manager/manager/menus">
			    <div class="ui warning message">
			        <div class="header">There was a problem</div>
			        <ul class="list">
			        </ul>
			    </div>
			    <div class="field" style="width: 96%; margin-left: 2%">
			        <label>Label</label>
			        <div class="ui left labeled input">
			            {{{label}}}
			            <div class="ui corner label">
			            	<i class="icon asterisk"></i>
			            </div>
			        </div>
			    </div>

			    <div class="field" style="width: 96%; margin-left: 2%">
			        <label>URL</label>
			        <div class="ui left labeled input">
			            {{{url}}}
			            <div class="ui corner label">
			                <i class="icon asterisk"></i>
			            </div>
			        </div>
			    </div>

			    <div class="field embedded" data-field="link" data-manager="menu_links" style="width: 96%; margin-left: 2%">
			        {{{link}}}
			    </div>
			    {{{id}}}
			    <input type="submit" class="fluid ui blue submit button" value="Save" style="margin-top: 20px; margin-left: 2%; width: 96%" />
			</form>
HBS;
		return $partial;
	}
}