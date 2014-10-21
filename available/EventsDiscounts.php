<?php
/*
 * @version 2
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsDiscounts.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsDiscounts {
	public $collection = 'Collection\Events';
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

	function codeField () {
		return [
			'name'		=> 'code',
			'label'		=> 'Code',
			'required'	=> true,
			'display'	=> 'Field\InputText'
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
			'display'	=> 'Field\Select',
			'nullable'	=> false,
			'default'	=> 'Amount'
		];
	}

	function expirationDateField() {
		return [
			'name'			=> 'expiration_date',
			'required'		=> true,
			'display'		=> 'Field\InputDatePicker',
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
			'display'	=> 'Field\InputText'
		];
	}

	public function indexPartial () {
		$partial = <<<'HBS'
			{{{ManagerEmbeddedIndexHeader label="Discount Codes"}}}
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
			    {{{ManagerEmbeddedIndexEmpty singular="Discount Code"}}}
	        {{/if}}
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			{{{ManagerEmbeddedFormHeader metadata=metadata}}}
		        {{{ManagerField . class="fluid" name="code" label="Code"}}}
			    {{{ManagerField . class="fluid" name="type" label="Type"}}}
			    {{{ManagerField . class="fluid" name="expiration_date" label="Expiration Date"}}}
			    {{{ManagerField . class="fluid" name="value" label="Value (Percentage/Amount)"}}}
			    {{{id}}}
				{{{form-token}}}
			{{{ManagerEmbeddedFormFooter}}}
HBS;
		return $partial;
	}
}

