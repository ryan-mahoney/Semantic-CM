<?php
/*
 * @version .5
 * @link https://raw.github.com/virtuecenter/manager/master/available/subcategories.php
 * @mode upgrade
 *
 * .3 wrong field referenced
 * .4 set correct lable for embedded doc
 * .5 delete feature
 */
namespace Manager;

class subcategories {
	private $field = false;
	public $collection = 'categories';
	public $title = 'Subcategories';
	public $titleField = 'title';
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
			{{#EmbeddedCollectionHeader Subcategories}}{{/EmbeddedCollectionHeader}}
			{{#if subcategory}}
				<table class="ui table manager segment">
					<thead>
						<tr><th>Title</th></tr>
						<tr><th class="trash">Delete</th></tr>
					</thead>
					<tbody>
						{{#each subcategory}}
							<tr data-id="{{dbURI}}">
								<td>{{title}}</td>
							</tr>
							<td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
						{{/each}}
					</tbody>
				</table>
		      {{else}}
			   {{#EmbeddedCollectionEmpty subcategory}}{{/EmbeddedCollectionEmpty}}
	       {{/if}}
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			{{#EmbeddedHeader}}{{/EmbeddedHeader}}

		        {{#FieldFull title Title}}{{/FieldFull}}

			    {{#FieldFull image Image}}{{/FieldFull}}

			    {{{id}}}

			{{#EmbeddedFooter}}{{/EmbeddedFooter}}
HBS;
		return $partial;
	}
}