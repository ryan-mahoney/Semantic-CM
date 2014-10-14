<?php
/*
 * @version .3
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsLinks.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsLinks {
	private $field = false;
	public $collection = 'events';
	public $title = 'Link/Menu';
	public $titleField = 'title';
	public $singular = 'Link/Menu';
	public $description = '{{count}} links';
	public $definition = 'Coming Soon';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'browser';
	public $category = 'Content';
	public $after = 'function';
	public $function = 'embeddedUpsert';
	public $notice = 'Link Saved';
	public $embedded = true;
	public $storage = [
		'collection' => 'events',
		'key' => '_id'
	];

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
	


	public function indexPartial () {
		$partial = <<<'HBS'
			{{#EmbeddedCollectionHeader label="Link / Menu"}}
			{{#if link_sub}}
				<table class="ui table manager segment">
					<thead>
						<tr>
							<th>Title</th>
							<th class="trash">Delete</th>
						</tr>
					</thead>
					<tbody>
						{{#each link_sub}}
							<tr data-id="{{dbURI}}">
							    <td>{{url}}</td>
								<td>{{title}}</td>
								<td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
							</tr>
						{{/each}}
					</tbody>
				</table>
		    {{else}}
			    {{#EmbeddedCollectionEmpty singular="Link / Menu"}}
	        {{/if}}
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			{{#EmbeddedHeader}}
	        {{#FieldFull url URL}}{{/FieldFull}}
		    {{#FieldFull title Title}}{{/FieldFull}}
		    {{#FieldFull target Redirect}}{{/FieldFull}}
		    {{{id}}}
			{{#EmbeddedFooter}}
HBS;
		return $partial;
	}
}

