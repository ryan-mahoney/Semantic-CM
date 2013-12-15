<?php
/*
 * @version .4
 * @link https://raw.github.com/virtuecenter/manager/master/available/subimages.php
 * @mode upgrade
 *
 * .3 bad field label
 * .4 delete feature
 */
namespace Manager;

class subimages {
	private $field = false;
	public $collection = 'subimages';
	public $title = 'Subimage';
	public $titleField = 'title';
	public $singular = 'Image';
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
		'collection' => 'photo_galleries',
		'key' => '_id'
	];

	public function __construct ($field=false) {
		$this->field = $field;
	}

	function captionField () {
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
			{{#EmbeddedCollectionHeader Images}}{{/EmbeddedCollectionHeader}}
			{{#if image_individual}}
				<table class="ui table manager segment">
					<thead>
						<tr><th>Caption</th></tr>
						<tr><th class="trash">Delete</th></tr>
					</thead>
					<tbody>
						{{#each image_individual}}
							<tr data-id="{{dbURI}}">
								<td>{{caption}}</td>
							</tr>
							<td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
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
			    
			    {{#FieldFull file Image}}{{/FieldFull}}

			    {{#FieldFull caption Caption}}{{/FieldFull}}

			    {{{id}}}

			{{#EmbeddedFooter}}{{/EmbeddedFooter}}    
HBS;
		return $partial;
	}
}