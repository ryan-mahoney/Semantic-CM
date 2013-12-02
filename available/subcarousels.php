<?php
/*
 * @version .5
 * @link https://raw.github.com/virtuecenter/manager/master/available/subcarousels.php
 * @mode upgrade
 *
 * .3 field name isssues
 * .4 typo
 * .5 missing caption field
 */
namespace Manager;

class subcarousels {
	private $field = false;
    public $collection = 'subcarousels';
    public $title = '"Sub Carousels"';
    public $titleField = 'title';
    public $singular = '"Sub Carousel"';
    public $description = '4 subcarousels';
    public $definition = '...';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $notice = 'Carousel Saved';
    public $storage = [
        'collection' => 'carousels',
        'key' => '_id'
    ];

    function imageField () {
		return [
			'name' => 'file',
			'label' => 'Image',
			'display' => 'InputFile'
		];
	}

	function urlField () {
		return [
			'name'		=> 'url',
			'label'		=> 'URL',
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

	function captionField () {
		return [
			'name'		=> 'caption',
			'label'		=> 'Caption',
			'required'	=> false,
			'display'	=> 'InputText'
		];
	}
	
	public function tablePartial () {
		$partial = <<<'HBS'
			{{#EmbeddedCollectionHeader Images}}{{/EmbeddedCollectionHeader}}
			{{#if subcarousel_individual}}
				<table class="ui table manager segment">
					<thead>
						<tr><th>Caption</th></tr>
					</thead>
					<tbody>
						{{#each carousel_individual}}
							<tr data-id="{{dbURI}}">
								<td>{{caption}}</td>
							</tr>
						{{/each}}
					</tbody>
				</table>
			{{else}}
				{{#EmbeddedCollectionEmpty carousel_individual}}{{/EmbeddedCollectionEmpty}}
			{{/if}}
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			{{#EmbeddedHeader}}{{/EmbeddedHeader}}
			    
			    {{#FieldFull file Image}}{{/FieldFull}}

			    {{#FieldFull url URL}}{{/FieldFull}}

			    {{#FieldFull target Target}}{{/FieldFull}}

			    {{#FieldFull caption Caption}}{{/FieldFull}}

			    {{{id}}}

			{{#EmbeddedFooter}}{{/EmbeddedFooter}}    
HBS;
		return $partial;
	}
}