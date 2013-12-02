<?php
/*
 * @version .2
 * @link https://raw.github.com/virtuecenter/manager/master/available/subcategories.php
 * @mode upgrade
 */
namespace Manager;

class subcategories {
	private $field = false;
	public $collection = 'categories';
	public $form = 'subcategories';
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
			{{#EmbeddedCollectionHeader Images}}{{/EmbeddedCollectionHeader}}
			{{#if categories_individual}}
				<table class="ui table manager segment">
					<thead>
						<tr><th>Title</th></tr>
					</thead>
					<tbody>
						{{#each categories}}
							<tr data-id="{{dbURI}}">
								<td>{{title}}</td>
							</tr>
						{{/each}}
					</tbody>
				</table>
		      {{else}}
			   {{#EmbeddedCollectionEmpty image}}{{/EmbeddedCollectionEmpty}}
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