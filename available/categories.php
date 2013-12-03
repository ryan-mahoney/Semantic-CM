<?php
/*
 * @version .4
 * @link https://raw.github.com/virtuecenter/manager/master/available/categories.php
 * @mode upgrade
 *
 * .3 duplicate field
 * .4 better handling of section
 */

namespace Manager;

class categories {
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
		return [
			'name' => 'section',
			'label' => 'Section',
			'required' => false,
			'transformIn' => function ($data) {
				if (is_array($data)) {
					return $data;
				}
				return $this->field->csvToArray($data);
			},
			'display' => 'InputToTags',
			'multiple' => false,
			'options' => function () {
				$existing = $this->db->distinct('categories', 'section');
				if (empty($existing)) {
					$existing = [];
				}
				$common = ['Blog', 'Books', 'Events', 'Links', 'Pages', 'Galleries', 'Podcasts', 'Sponsors', 'Videos'];
				$categories = array_unique(array_merge($existing, $common));
				sort($categories);
				return $categories;
			}
		];
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