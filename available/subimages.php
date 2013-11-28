<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/subimages.php
 * @mode upgrade
 */
namespace Manager;

class subimages {
	private $field = false;
	public $collection = 'subimages';
	public $form = 'subimages';
	public $title = 'Subimage';
	public $singular = 'Menu';
	public $description = '4 subimages';
	public $definition = '';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'browser';
	public $category = 'Content';
	public $after = 'function';
	public $function = 'embeddedUpsert';
	public $notice = 'Subimages';
	public $embedded = true;
	public $storage = [
		'collection' => 'subimages',
		'key' => '_id'
	];

	public function __construct ($field=false) {
		$this->field = $field;
	}

	function urlField () {
		return [
			'name'		=> 'caption',
			'label'		=> 'Caption',
			'required'	=> false,
			'display'	=> 'InputText'
		];
	}

	function titleField () {
		return [
			'name'		=> 'copyright',
			'label'		=> 'Copyright',
			'required'	=> false,
			'display'	=> 'InputText'
		];
	}

	function imageField () {
		return [
			'name' => 'file',
			'label' => 'Image',
			'display' => 'InputFile'
		];
	}

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
					{{#each subimages}}
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
			            {{{file}}}
			            <div class="ui corner label">
			            	<i class="icon asterisk"></i>
			            </div>
			        </div>
			    </div>

			    <div class="field" style="width: 96%; margin-left: 2%">
			        <label>URL</label>
			        <div class="ui left labeled input">
			            {{{caption}}}
			            <div class="ui corner label">
			                <i class="icon asterisk"></i>
			            </div>
			        </div>
			    </div>

				<div class="field" style="width: 96%; margin-left: 2%">
			        <label>Target</label>
			        <div class="ui left labeled input">
			            {{{copyright}}}
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