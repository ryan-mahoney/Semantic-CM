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
    public $title = 'Categories';
    public $titleField = 'title';
    public $singular = 'Category';
    public $description = '4 categories';
    public $definition = '.....';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'SEO'];
    public $icon = 'checkmark sign';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
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

    function code_nameField () {
		return [
			'name' => 'code_name',
			'display'	=> 'InputText'
		];
	}

	function metakeywordsField () {
		return [
			'name' => 'metadata_keywords',
			'display'	=> 'InputText'
		];
	}

	function metadescriptionField () {
		return [
			'name' => 'metadata_description',
			'display'	=> 'InputText'
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
	
*/

	public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#if categories}}
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
		            {{else}}
					{{#CollectionEmpty}}{{/CollectionEmpty}}
				{{/if}}
            </div>
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
   	     	{{#Form}}{{/Form}}
	            <div class="top-container">
	                {{#DocumentHeader}}{{/DocumentHeader}}
	                {{#DocumentTabs}}{{/DocumentTabs}}
	            </div>

	            <div class="bottom-container">
	                <div class="ui tab active" data-tab="Main">
	                    {{#DocumentFormLeft}}
	                        {{#FieldLeft title Title required}}{{/FieldLeft}}
	                        {{#FieldLeft section Section required}}{{/FieldLeft}}
						    {{#FieldLeft image Image}}{{/FieldLeft}}
						    {{#FieldEmbedded subcategory subcategories}}{{/FieldEmbedded}}
	                        {{{id}}}
	                    {{/DocumentFormLeft}}                 
	                
	                    {{#DocumentFormRight}}
	                	    {{#DocumentButton}}{{/DocumentButton}}
		                    {{#FieldFull status}}{{/FieldFull}}
		                    <br />
		                    {{#FieldLeft featured}}{{/FieldLeft}}
	                    {{/DocumentFormRight}}
	                </div>
	                <div class="ui tab" data-tab="SEO">
		                {{#DocumentFormLeft}}
		                    {{#FieldLeft code_name Slug}}{{/FieldLeft}}
		                    {{#FieldLeft metadata_description Description}}{{/FieldLeft}}
		              		{{#FieldLeft metadata_keywords Keywords}}{{/FieldLeft}}
		                {{/DocumentFormLeft}}
		                
		                {{#DocumentFormRight}}
			                {{#DocumentButton}}{{/DocumentButton}}
		                {{/DocumentFormRight}}
		            </div>
		        </div>
		    </form>
HBS;
        return $partial;
    }
}