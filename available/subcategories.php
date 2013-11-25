<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/subcategories.php
 * @mode upgrade
 */
namespace Manager;

class subcategories {
	private $field = false;
	public $collection = 'categories';
	public $form = 'subcategories';
	public $title = 'Subcategories';
	public $singular = 'Subcategory';
	public $description = '4 subcategories';
	public $definition = '';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'browser';
	public $category = 'Content';
	public $after = 'function';
	public $function = 'embeddedUpsert';
	public $notice = 'Subcategory Saved';
	public $embedded = true;
	public $storage = [
		'collection' => 'categories',
		'key' => '_id'
	];

	public function __construct ($field=false) {
		$this->field = $field;
	}

	function titleField () {
		return [
			'name'		=> 'title',
			'label'		=> 'Title',
			'required'	=> false,
			'display'	=> 'InputText'
		];
	}
	
	function imageField () {
		return [
			'name' => 'image',
			'label' => 'Image',
			'display' => 'InputFile'
		];
	}

	public function tablePartial () {
		$partial = <<<'HBS'
			<a class="item">Subcategories</a>
			<!-- <div class="ui borderless pagination menu large fluid"></div> -->
			<div class="item right">
        		<div class="ui button manager add">Add</div>
    		</div>
			<table class="ui table manager segment">
				<thead>
					<tr><th>Title</th></tr>
				</thead>
				<tbody>
					{{#each subcategory}}
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
    		<form class="ui form" data-xhr="true" method="post" action="/Manager/manager/subcategories">
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
			        <label>Image</label>
			        <div class="ui left labeled input">
			            {{{image}}}
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