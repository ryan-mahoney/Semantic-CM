<?php
/*
 * @version .2
 * @link https://raw.github.com/virtuecenter/manager/master/available/blurbs.php
 * @mode upgrade
 */
namespace Manager;

class blurbs {
	private $field = false;
	public $collection = 'blurbs';
	public $form = 'blurbs';
	public $title = 'Blurbs';
	public $singular = 'Blurb';
	public $description = '5 blurbs';
	public $definition = 'Blurbs are used to control small blocks of text on a website.';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'basic content';
	public $category = 'Content';
	public $notice = 'Blurb Saved';
	public $storage = [
		'collection' => 'blurbs',
		'key' => '_id'
	];

	public function __construct ($field=false) {
		$this->field = $field;
	}
	
	function titleField () {
		return [
			'name' => 'title',
			'placeholder' => 'Title',
			'required' => true,
			'display' => 'InputText'
		];
	}

	function bodyField () {
		return [
			'name' => 'body',
			'required' => false,
			'display' => 'Ckeditor'		
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

	public function tablePartial () {
		$partial = <<<'HBS'
			<div class="top-container">
				{{#CollectionHeader}}{{/CollectionHeader}}
			</div>

			<div class="bottom-container">
				{{#if blurbs}}
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
				   			{{#each blurbs}}
								<tr data-id="{{dbURI}}">
									<td>{{title}}</td>
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
				{{else}}
					{{#CollectionEmpty}}{{/CollectionEmpty}}
				{{/if}}
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
		    <div class="bottom-container">
			    {{#DocumentFormLeft}}
			    	{{#FieldLeft title Title required}}{{/FieldLeft}}
			    	{{#FieldLeft body required}}{{/FieldLeft}}
			    	{{{id}}}
			    {{/DocumentFormLeft}}
				{{#DocumentFormRight}}{{/DocumentFormRight}}
			</div>
HBS;
		return $partial;
	}
}