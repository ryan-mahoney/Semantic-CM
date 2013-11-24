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

	function sectionField () {
		return array(
			'name' => 'section',
			'label' => 'Section',
			'required' => true,
			'display' => 'InputText'
		);
	}

	function titleField () {
		return array(
			'name' => 'title',
			'label' => 'Title',
			'required' => true,
			'display' => 'InputText'
		);
	}
	
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
	

	function imageField () {
		return array(
			'name' => 'image',
			'label' => 'Image',
			'display' => 'InputFile'
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
	
	public function subcategoryField() {
		return array(
			'name' => 'subcategory',
			'label' => 'Sub Category',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> 'vc\ms\site\subdocuments\CategorySubAdmin'
		);
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

	function defaultTable () {
		return array (
			'columns' => array(
				array('section', '25%', 'Section', false),
				array('title', '70%', 'Title', false)
			),
			'title' => 'Categories',
			'link' => 'title',
			'sort' => array('section' => 1, 'title' => 1),
			'features' => array('delete', 'search', 'add', 'edit', 'pagination', 'sortable')
		);
	}
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
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each blogs}}
                            <tr data-id="{{dbURI}}">
                                <td>{{title}}</td>
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
                    {{#FieldLeft label Label required}}{{/FieldLeft}}
                    {{#FieldLeft url URL required}}{{/FieldLeft}}
                    {{#FieldEmbedded link menu_links}}{{/FieldEmbedded}}
                    {{{id}}}
                {{/DocumentFormLeft}}                 
                
                {{#DocumentFormRight}}
                {{/DocumentFormRight}}
            </div>
HBS;
        return $partial;
    }
}