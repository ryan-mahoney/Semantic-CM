<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsExceptions.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsExceptions {
	private $field = false;
	public $collection = 'events';
	public $title = 'Exception Dates';
	public $titleField = 'title';
	public $singular = 'Exception Date';
	public $description = '{{count}} exceptions';
	public $definition = 'Coming Soon';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'browser';
	public $category = 'Content';
	public $after = 'function';
	public $function = 'embeddedUpsert';
	public $notice = 'Discount Saved';
	public $embedded = true;
	public $storage = [
		'collection' => 'events',
		'key' => '_id'
	];
	
	function dateField() {
		return [
			'name'			=> 'date',
			'required'		=> true,
			'display'		=> 'InputDatePicker',
			'transformIn'	=> function ($data) {
				return new \MongoDate(strtotime($data));
			},
			'transformOut'	=> function ($data) {
				return date('m/d/Y', $data->sec);
			},
			'default'		=> function () {
				return date('m/d/Y');
			}
		];
	}

	function noticeField () {
		return [
			'name'		=> 'notice',
			'label'		=> 'Notice',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}


	public function tablePartial () {
		$partial = <<<'HBS'
			{{#EmbeddedCollectionHeader label="Exceptions Dates"}}
			{{#if exception_date}}
				<table class="ui table manager segment">
					<thead>
						<tr>
							<th>Title</th>
							<th class="trash">Delete</th>
						</tr>
					</thead>
					<tbody>
						{{#each exception_date}}
							<tr data-id="{{dbURI}}">
							    <td>{{date}}</td>
								<td>{{notice}}</td>
								<td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
							</tr>
						{{/each}}
					</tbody>
				</table>
		    {{else}}
			    {{#EmbeddedCollectionEmpty singular="Exception Date"}}
	        {{/if}}
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			{{#EmbeddedHeader}}
	        {{#FieldFull date Date}}{{/FieldFull}}
		    {{#FieldFull notice Notice}}{{/FieldFull}}
		    {{{id}}}
			{{#EmbeddedFooter}}
HBS;
		return $partial;
	}
}

