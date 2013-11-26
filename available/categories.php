<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/categories.php
 * @mode upgrade
 */

namespace Manager;


class categories{
	private $field = false;
    public $collection = 'categories';
    public $form = 'categories';
    public $title = 'Categories';
    public $singular = 'Category';
    public $description = '4 categories';
    public $definition = '.....';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'checkmark sign';
    public $category = 'Content';
    public $notice = 'Category Saved';
    public $storage = [
        'collection' => 'categories',
        'key' => '_id'
    ];

	function titleField () {
		return array(
			'name' => 'title',
			'label' => 'Title',
			'required' => true,
			'display' => 'InputText'
		);
	}
	
	function sectionField () {
		return array(
			'name' => 'section',
			'label' => 'Section',
			'required' => true,
			'display' => 'InputText'
		);
	}

	function imageField () {
		return array(
			'name' => 'image',
			'label' => 'Image',
			'display' => 'InputFile'
		);
	}

	public function subcategoryField() {
		return [
			'name' => 'subcategory',
			'label' => 'Sub Categories',
			'required' => false,
			'display'	=>	'Manager',
			'manager'	=> 'subcategories'
		];
	}

	function statusField () {
		return [
			'name'		=> 'status',
			'required'	=> true,
			'options'	=> array(
				'published'	=> 'Published',
				'draft'		=> 'Draft'
			),
			'display'	=> 'Select',
			'nullable'	=> false,
			'default'	=> 'published'
		];
	}

	function featuredField () {
        return [
            'name' => 'featured',
            'label' => 'Feature',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

	/*

	function tagsField () {
		return array(
			'name' => 'tags',
			'label' => 'Tags for Categories',
			'required' => false,
			'transformIn' => function ($data) {
				return VCPF\Regex::csvToArray($data);
			},
			'display' => VCPF\Field::inputToTags(),
			'autocomplete' => function () {
				return VCPF\Model::mongoDistinct('categories', 'tags');
			},
			'tooltip' => 'Another way to make entries more findable.'
		);
	}	

	function featuredField () {
		return array(
			'name' => 'featured',
			'label' => false,
			'required' => false,
			'options' => array(
				't' => 'Yes',
				'f' => 'No'
			),
			'display' => 'InputRadioButton',
			'default' => 'f'
		);
	}
	
	function templateField () {
		return [
			'name' => 'template',
			'label' => 'Type',
			'required' => true,
			'options' => function () {
				$templates = VCPF\Config::category()['templates'];
				if (!is_array($templates) || count($templates) == 0) {
					$templates = ['__vc__ms__site__admin__CategoryAdmin' => 'Basic'];
				}
				return $templates;
			},
			'display' => VCPF\Field::select(),
			//'nullable' => 'Choose a Template'
		];
	}
	
	function code_nameField () {
		return array_merge(
			VCPF\DOMFormTableArray::codename('title', 'categories'),
			[
				'path' => '/category/',
				'selector' => '#title-field input',
				'mode' => 'after'
			]
		);
	}
*/

	public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#CollectionPagination}}{{/CollectionPagination}}
                {{#CollectionButtons}}{{/CollectionButtons}}
                
                <table class="ui large table segment manager">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Section</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each categories}}
                            <tr data-id="{{dbURI}}">
                                <td>{{title}}</td>
                                <td>{{section}}</td>
                                <td>
                                    <div class="manager trash ui icon button">
                                         <i class="trash icon"></i>
                                     </div>
                                 </td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>

                {{#CollectionPagination}}{{/CollectionPagination}}
            </div>
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#DocumentHeader}}{{/DocumentHeader}}
                {{#DocumentTabsButtons}}{{/DocumentTabsButtons}}
            </div>

            <div class="bottom-container">
                {{#DocumentFormLeft}}
                    {{#FieldLeft title Title required}}{{/FieldLeft}}
                    {{#FieldLeft section Section required}}{{/FieldLeft}}
					{{#FieldLeft image Image}}{{/FieldLeft}}
					{{#FieldEmbedded subcategory subcategories}}{{/FieldEmbedded}}
                    {{{id}}}
                {{/DocumentFormLeft}}                 
                
                {{#DocumentFormRight}}
                {{#FieldFull status}}{{/FieldFull}}
                <br />
                {{#FieldLeft featured}}{{/FieldLeft}}
                {{/DocumentFormRight}}
            </div>
HBS;
        return $partial;
    }
}