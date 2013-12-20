<?php
/*
 * @version .3
 * @link https://raw.github.com/virtuecenter/manager/master/available/department_profiles.php
 * @mode upgrade
 * .3 definiton and description for count added
 */
namespace Manager;

class department_profiles {
	private $field = false;
    public $collection = 'department_profiles';
    public $title = '"Department Profiles"';
    public $titleField = 'title';
    public $singular = '"Department Profile"';
    public $description = '{{count}} "department profiles"';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'department_profiles',
        'key' => '_id'
    ];

	public function __construct ($field=false) {
		$this->field = $field;
	}

	function titleField () {
		return [
			'name'		=> 'title',
			'label'		=> 'Title',
			'required'	=> false,
			'display'	=> 'InputText'
		];
	}
	
	function imageField () {
		return [
			'name' => 'image',
			'label' => 'Image',
			'display' => 'InputFile'
		];
	}

	public function tablePartial () {
		$partial = <<<'HBS'
			{{#EmbeddedCollectionHeader Images}}{{/EmbeddedCollectionHeader}}
			{{#if department_profiles}}
				<table class="ui table manager segment">
					<thead>
						<tr><th>Title</th></tr>
					</thead>
					<tbody>
						{{#each department_profiles}}
							<tr data-id="{{dbURI}}">
								<td>{{title}}</td>
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

		        {{#FieldFull title Title}}{{/FieldFull}}

			    {{#FieldFull image Image}}{{/FieldFull}}

			    {{{id}}}

		{{#EmbeddedFooter}}{{/EmbeddedFooter}}
HBS;
		return $partial;
	}
}