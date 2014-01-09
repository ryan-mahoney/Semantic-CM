<?php
/*
 * @version .3
 * @link https://raw.github.com/virtuecenter/manager/master/available/events_plus.php
 * @mode upgrade
 *
 */
namespace Manager;

class events_plus {
	private $field = false;
	public $collection = 'events';
	public $title = 'Included Dates';
	public $titleField = 'title';
	public $singular = 'Included Date';
	public $description = '{{count}} dates';
	public $definition = 'Coming Soon';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'browser';
	public $category = 'Content';
	public $after = 'function';
	public $function = 'embeddedUpsert';
	public $notice = 'Date Saved';
	public $embedded = true;
	public $storage = [
		'collection' => 'events',
		'key' => '_id'
	];

	public function __construct ($field=false) {
		$this->field = $field;
	}

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
			'name' => 'notice',
			'label' => 'Notice',
			'display'	=> 'InputText'
		];
	}

	public function tablePartial () {
		$partial = <<<'HBS'
			{{#EmbeddedCollectionHeader label="Included Dates"}}
			{{#if plus_date}}
				<table class="ui table manager segment">
					<thead>
						<tr>
							<th>Title</th>
							<th class="trash">Delete</th>
						</tr>
					</thead>
					<tbody>
						{{#each plus_date}}
							<tr data-id="{{dbURI}}">
								<td>{{notice}}</td>
								<td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
							</tr>
						{{/each}}
					</tbody>
				</table>
		    {{else}}
			    {{#EmbeddedCollectionEmpty singular="Included Date"}}
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

