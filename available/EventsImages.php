<?php
/*
 * @version .4
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsImages.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsImages {
	private $field = false;
	public $collection = 'events';
	public $title = 'Venue Images';
	public $titleField = 'title';
	public $singular = 'Venue Image';
	public $description = '{{count}} images';
	public $definition = 'Coming Soon';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'browser';
	public $category = 'Content';
	public $after = 'function';
	public $function = 'embeddedUpsert';
	public $notice = 'Venue Saved';
	public $embedded = true;
	public $storage = [
		'collection' => 'events',
		'key' => '_id'
	];

	function imageField () {
		return [
			'name' => 'image',
			'label' => 'Image',
			'display' => 'InputFile'
		];
	}

	function titleField () {
		return [
			'name'		=> 'heading',
			'label'		=> 'Heading',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function descriptionField () {
		return [
			'name' => 'description',
			'label' => 'Description',
			'display' => 'Textarea'
		];
	}


	public function tablePartial () {
		$partial = <<<'HBS'
			{{#EmbeddedCollectionHeader label="Venue Images"}}
			{{#if image_sub}}
				<table class="ui table manager segment">
					<thead>
						<tr>
							<th>Image</th>
							<th>Title</th>
							<th class="trash">Delete</th>
						</tr>
					</thead>
					<tbody>
						{{#each image_sub}}
							<tr data-id="{{dbURI}}">
							    <td>{{#ImageResize}}{{image}}{{/ImageResize}}</td>
								<td>{{heading}}</td>
								<td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
							</tr>
						{{/each}}
					</tbody>
				</table>
		    {{else}}
			    {{#EmbeddedCollectionEmpty singular="Venue Image"}}
	        {{/if}}
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			{{#EmbeddedHeader}}
	        {{#FieldFull image Image}}{{/FieldFull}}
		    {{#FieldFull heading Heading}}{{/FieldFull}}
		    {{#FieldFull description Description}}{{/FieldFull}}
		    {{{id}}}
			{{#EmbeddedFooter}}
			<div style="padding-bottom:100px"></div>
HBS;
		return $partial;
	}
}

