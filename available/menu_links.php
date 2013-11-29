<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/menu_links.php
 * @mode upgrade
 */
namespace Manager;

class menu_links {
	private $field = false;
	public $collection = 'menus';
	public $title = 'Menus';
	public $titleField = 'title';
	public $singular = 'Menu';
	public $description = '4 menu items';
	public $definition = '';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'browser';
	public $category = 'Content';
	public $after = 'function';
	public $function = 'embeddedUpsert';
	public $notice = 'Menu Saved';
	public $embedded = true;
	public $storage = [
		'collection' => 'menus',
		'key' => '_id'
	];

	public function __construct ($field=false) {
		$this->field = $field;
	}

	function urlField () {
		return [
			'name'		=> 'url',
			'label'		=> 'URL',
			'required'	=> false,
			'display'	=> 'InputText'
		];
	}

	function titleField () {
		return [
			'name'		=> 'title',
			'label'		=> 'Title',
			'required'	=> false,
			'display'	=> 'InputText'
		];
	}
	
	function targetField () {
		return [
			'name'		=> 'target',
			'label'		=> 'Redirect',
			'required'	=> true,
			'options'	=> [
				'_self'		=> 'Self',
				'_blank'	=> 'Blank',
				'_top'		=> 'Top',
				'_parent'	=> 'Parent'
			],
			'display'	=> 'Select',
			'nullable'	=> false,
			'default'	=> 'self'
		];
	}
/*	
	function imageField () {
		return [
			'name' => 'file',
			'label' => 'Image',
			'display' => 'InputFile'
		];
	}
*/

	public function tablePartial () {
		$partial = <<<'HBS'
			<a class="item">Sub-Menus</a>
			<!-- <div class="ui borderless pagination menu large fluid"></div> -->
			<div class="item right">
        		<div class="ui button manager add">Add</div>
    		</div>
			<table class="ui table manager segment">
				<thead>
					<tr><th>Label</th></tr>
				</thead>
				<tbody>
					{{#each link}}
						<tr data-id="{{dbURI}}">
							<td>{{title}}</td>
						</tr>
					{{/each}}
				</tbody>
			</table>
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			<div class="header">
          		Sub Menu
      		</div>
    		<form class="ui form" data-xhr="true" method="post" action="/Manager/manager/menu_links">
			    <div class="ui warning message">
			        <div class="header">There was a problem</div>
			        <ul class="list">
			        </ul>
			    </div>
			    <div class="field" style="width: 96%; margin-left: 2%">
			        <label>Title</label>
			        <div class="ui left labeled input">
			            {{{title}}}
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

				<div class="field" style="width: 96%; margin-left: 2%">
			        <label>Target</label>
			        <div class="ui left labeled input">
			            {{{target}}}
			            <div class="ui corner label">
			                <i class="icon asterisk"></i>
			            </div>
			        </div>
			    </div>

			    {{{id}}}

			    <div class="actions">          
			    	<div class="ui black button embedded close">Close</div>          
			    	<button class="ui positive right labeled icon button">Save<i class="checkmark icon"></i></button>
			   	</div>
			</form>
HBS;
		return $partial;
	}
}