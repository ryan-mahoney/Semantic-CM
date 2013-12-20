<?php
/*
 * @version .6
 * @link https://raw.github.com/virtuecenter/manager/master/available/subimages.php
 * @mode upgrade
 *
 * .3 bad field label
 * .4 delete feature
 * .5 missing image field
 * .6 definition and description for count added
 */
namespace Manager;

class subimages {
	private $field = false;
	public $collection = 'subimages';
	public $title = 'Subimage';
	public $titleField = 'title';
	public $singular = 'Image';
	public $description = '{{count}} subimages';
	public $definition = 'Coming Soon';
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
				<table class="ui table manager segment manager sortable">
				      <col width="10%">
	                  <col width="40%">
	                  <col width="40%">
	                  <col width="10%">
					<thead>
					        <tr>
							    <th><i class="shuffle basic icon"></i></th>
						    </tr>
							<tr><th>Image</th></tr>
							<tr><th>Caption</th></tr>
							<tr><th class="trash">Delete</th></tr>
					</thead>
					<tbody>
						{{#each image_individual}}
							<tr data-id="{{dbURI}}">
							    <td class="handle"><i class="reorder icon"></i></td>
								<td>{{#ImageResize}}{{file}}{{/ImageResize}}</td>
								<td>{{caption}}</td>
							    <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
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
			    
			    {{#FieldFull file Image}}{{/FieldFull}}

			    {{#FieldFull caption Caption}}{{/FieldFull}}

			    {{{id}}}

			{{#EmbeddedFooter}}{{/EmbeddedFooter}}    
HBS;
		return $partial;
	}
}