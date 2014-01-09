<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/events_discounts.php
 * @mode upgrade
 *
 */
namespace Manager;

class events_discounts {
	private $field = false;
	public $collection = 'events';
	public $title = 'Discount Codes';
	public $titleField = 'title';
	public $singular = 'events_discounts';
	public $description = '{{count}} discounts';
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

	public function __construct ($field=false) {
		$this->field = $field;
	}

	function codeField () {
		return [
			'name'		=> 'code',
			'label'		=> 'Code',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function typeField () {
		return [
			'name'		=> 'type',
			'required'	=> true,
			'options'	=> array(
				'published'	=> 'Percent',
				'draft'		=> 'Amount'
			),
			'display'	=> 'Select',
			'nullable'	=> false,
			'default'	=> 'Amount'
		];
	}

	function expirationDateField() {
		return [
			'name'			=> 'expiration_date',
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

	function valueField () {
		return [
			'name'		=> 'value',
			'label'		=> 'Value (Percentage/Amount)',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}


	public function tablePartial () {
		$partial = <<<'HBS'
			{{#EmbeddedCollectionHeader label="Discount Codes"}}
			{{#if events_discounts}}
				<table class="ui table manager segment">
					<thead>
						<tr>
							<th>Title</th>
							<th class="trash">Delete</th>
						</tr>
					</thead>
					<tbody>
						{{#each events_discounts}}
							<tr data-id="{{dbURI}}">
								<td>{{title}}</td>
								<td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
							</tr>
						{{/each}}
					</tbody>
				</table>
		    {{else}}
			    {{#EmbeddedCollectionEmpty singular="Discount Codes"}}
	        {{/if}}
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			{{#EmbeddedHeader}}
	        {{#FieldFull code Code}}{{/FieldFull}}
		    {{#FieldFull type}}{{/FieldFull}}
		    {{#FieldFull expiration_date}}{{/FieldFull}}
		    {{#FieldFull value "Value (Percentage/Amount)"}}{{/FieldFull}}
		    {{{id}}}
			{{#EmbeddedFooter}}
HBS;
		return $partial;
	}
}

