<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/subcarousels.php
 * @mode upgrade
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
/*
	function captionField () {
		return [
			'name'		=> 'caption',
			'label'		=> 'Caption',
			'required'	=> false,
			'display'	=> 'Ckeditor'
		];
	}


	function titleField () {	
		return [
			'name' => 'title',
			'label' => 'Title',
			'required' => true,
			'display' => 'InputText'
		];
	}


	function afterFieldsetUpdate () {
		return function ($admin) {
			$DOM = VCPF\DOMView::getDOM();
			$DOM['#image_individual-field .table-actions']->append('<a class="btn btn-small vcms-panel" data-id="" data-attributes="{\'gallery\':\'' . (string)$admin->activeRecord['_id'] . '\'}" data-mode="save" data-vc__admin="vc\ms\site\admin\ImageBatchAdmin" style="float: right">Upload Batch</a>');
		};
	}
	
	function code_nameField () {
		return array_merge(
			VCPF\DOMFormTableArray::codename('title', 'photo_galleries'),
			[
				'path' => '/gallery/',
				'selector' => '#title-field input',
				'mode' => 'after'
			]
		);
	}
	
	public function image_individualField() {
		return array(
			'name' => 'image_individual',
			'label' => 'Add Individual Image',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> 'vc\ms\site\subdocuments\ImageSubAdmin'
		);
	}
	
	
	function display_dateField() {
		return array(
			'name'=> 'display_date',
			'label'=> 'Display Date',
			'required'=>true,
			'display' => VCPF\Field::inputDatePicker(),
			'transformIn' => function ($data) {
				return new \MongoDate(strtotime($data));
			},
			'transformOut' => function ($data) {
				return date('m/d/Y', $data->sec);
			},
			'default' => function () {
				return date('m/d/Y', (strtotime('now')));
			}
		);
	}

		
	*/
	
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
				{{#EmbeddedCollectionEmpty image}}{{/EmbeddedCollectionEmpty}}
			{{/if}}
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			{{#EmbeddedHeader}}{{/EmbeddedHeader}}
			    
			    {{#FieldFull file Image}}{{/FieldFull}}

			    {{#FieldFull url URL}}{{/FieldFull}}4

			    {{#FieldFull target Target}}{{/FieldFull}}

			    {{#FieldFull caption Caption}}{{/FieldFull}}

			    {{{id}}}

			{{#EmbeddedFooter}}{{/EmbeddedFooter}}    
HBS;
		return $partial;
	}
}