<?php
/*
 * @version .4
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsDiscounts.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsDiscounts {
	private $field = false;
	public $collection = 'events';
	public $title = 'Discount Codes';
	public $titleField = 'title';
	public $singular = 'Discount Code';
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
			{{#if discount_code}}
				<table class="ui table manager segment">
					<thead>
						<tr>
							<th>Title</th>
							<th class="trash">Delete</th>
						</tr>
					</thead>
					<tbody>
						{{#each discount_code}}
							<tr data-id="{{dbURI}}">
								<td>{{code}}</td>
								<td>{{type}}</td>
								<td>{{expiration_date}}</td>
								<td>{{value}}</td>
								<td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
							</tr>
						{{/each}}
					</tbody>
				</table>
		    {{else}}
			    {{#EmbeddedCollectionEmpty singular="Discount Code"}}
	        {{/if}}
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			{{#EmbeddedHeader}}
	        {{#FieldFull code Code}}{{/FieldFull}}
		    {{#FieldFull type Type}}{{/FieldFull}}
		    {{#FieldFull expiration_date "Expiration Date"}}{{/FieldFull}}
		    {{#FieldFull value "Value (Percentage/Amount)"}}{{/FieldFull}}
		    {{{id}}}
			{{#EmbeddedFooter}}
HBS;
		return $partial;
	}
}

