<?php
/*
 * @version 1.1
 * @link https://raw.github.com/virtuecenter/manager/master/available/subcarousels.php
 * @mode upgrade
 *
 * .3 field name isssues
 * .4 typo
 * .5 missing caption field
 * .6 change save function
 * .7 bad field name
 * .8 wrong collection
 * .9 mark as embedded
 * 1.0 delete feature 
 * 1.1 definition and description for count added
 */
namespace Manager;

class subcarousels {
	private $field = false;
    public $collection = 'carousels';
    public $title = 'SubCarousels';
    public $titleField = 'title';
    public $singular = 'SubCarousel';
    public $description = '{{count}} subcarousels';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $embedded = true;
    public $function = 'embeddedUpsert';
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
			{{#if carousel_individual}}
				<table class="ui table manager segment">
					<thead>
						<tr><th>Caption</th></tr>
						<tr><th class="trash">Delete</th>
					</thead>
					<tbody>
						{{#each carousel_individual}}
							<tr data-id="{{dbURI}}">
								<td>{{caption}}</td>
								<td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
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