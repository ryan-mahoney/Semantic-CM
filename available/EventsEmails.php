<?php
/*
 * @version .4
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsEmails.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsEmails {
	public $collection = 'events';
	public $title = 'Emails';
	public $titleField = 'title';
	public $singular = 'Email';
	public $description = '{{count}} emails';
	public $definition = 'Coming Soon';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'browser';
	public $category = 'Content';
	public $after = 'function';
	public $function = 'embeddedUpsert';
	public $notice = 'Email Saved';
	public $embedded = true;
	public $storage = [
		'collection' => 'events',
		'key' => '_id'
	];

	function subjectField () {
		return [
			'name'		=> 'email_subject',
			'label'		=> 'Subject',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function bodyField () {
		return [
			'display' => 'Ckeditor',
			'name' => 'email_body'
		];
	}

	function titleField () {
		return [
			'name'		=> 'title',
			'label'		=> 'Title',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function sendDateField() {
		return [
			'name'			=> 'send_date',
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

	function typeField () {
		return [
			'name' => 'type',
			'label' => 'Type',
			'required' => true,
			'display' => 'InputToTags',
			'multiple' => false,
			'options' => function () {
				$existing = $this->db->distinct('events_emails', 'type');
				if (empty($existing)) {
					$existing = [];
				}
				$common = ['Choose Message Type', 'Welcome', 'Reminder', 'Thankyou', 'Receiept'];
				$emailType = array_unique(array_merge($existing, $common));
				sort($emailType);
				return $emailType;
			}
		];
	}

	function ccField () {
		return array(
			'name' => 'cc',
			'label' => 'Carbon Copy To',
			'required' => true,
			'display' => 'InputText'
		);
	}

	function bccField () {
		return array(
			'name' => 'bcc',
			'label' => 'Blind Carbon Copy To',
			'required' => true,
			'display' => 'InputText'
		);
	}

	function tagsField () {
		return [
			'name' => 'tags',
			'label' => 'Tags',
			'required' => false,
			'transformIn' => function ($data) {
				if (is_array($data)) {
					return $data;
				}
				return $this->field->csvToArray($data);
			},
			'display' => 'InputToTags',
			'multiple' => true,
			'options' => function () {
				return $this->db->distinct('events_emails', 'tags');
			}
		];
	}


	public function indexPartial () {
		$partial = <<<'HBS'
			{{#EmbeddedCollectionHeader label="Emails"}}
			{{#if email_sub}}
				<table class="ui table manager segment">
					<thead>
						<tr>
							<th>Title</th>
							<th class="trash">Delete</th>
						</tr>
					</thead>
					<tbody>
						{{#each email_sub}}
							<tr data-id="{{dbURI}}">
							    <td>{{title}}</td>
								<td>{{email_subject}}</td>
								<td>{{type}}</td>
								<td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
							</tr>
						{{/each}}
					</tbody>
				</table>
		    {{else}}
			    {{#EmbeddedCollectionEmpty singular="Email"}}
	        {{/if}}
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			{{#EmbeddedHeader}}
	        {{#FieldFull from_address "From Address"}}{{/FieldFull}}
		    {{#FieldFull email_subject}}{{/FieldFull}}
		    {{#FieldFull email_body}}{{/FieldFull}}
		    {{#FieldFull title Title}}{{/FieldFull}}
		    {{#FieldFull send_date "Send Date"}}{{/FieldFull}}
		    {{#FieldFull type Type}}{{/FieldFull}}
		    {{#FieldFull cc "CarbonCopy To"}}{{/FieldFull}}
		    {{#FieldFull bcc "Blind Carbon Copy To"}}{{/FieldFull}}
		    {{#FieldFull tags Tags}}{{/FieldFull}}
		    {{{id}}}
			{{#EmbeddedFooter}}
			<div style="padding-bottom:100px"></div>
HBS;
		return $partial;
	}
}

