<?php
/*
 * @version .4
 * @link https://raw.github.com/virtuecenter/manager/master/available/department_profiles.php
 * @mode upgrade
 * .3 definiton and description for count adde
 * .4 fixes
 */
namespace Manager;

class department_profiles {
    private $field = false;
    public $collection = 'department_profiles';
    public $title = 'Profiles';
    public $titleField = 'title';
    public $singular = 'Profile';
    public $description = '{{count}} profiles';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $embedded = true;
    public $storage = [
        'collection' => 'departments',
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
			{{#EmbeddedCollectionHeader department_profiles}}{{/EmbeddedCollectionHeader}}
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
			   {{#EmbeddedCollectionEmpty Profile}}{{/EmbeddedCollectionEmpty}}
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
