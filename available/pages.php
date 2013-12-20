<?php
/*
 * @version .5
 * @link https://raw.github.com/virtuecenter/manager/master/available/pages.php
 * @mode upgrade
 *
 * .4 remove sort
 * .5 definiton and description for count added
 */
namespace Manager;

class pages {
	private $field = false;
    public $collection = 'pages';
    public $title = 'Pages';
    public $titleField = 'title';
    public $singular = 'Page';
    public $description = '{{count}} pages';
    public $definition = 'Individual content pages can be created and linked to your site through menus. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'SEO'];
    public $icon = 'copy';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'pages',
        'key' => '_id'
    ];

	public function __construct ($field=false) {
		$this->field = $field;
	}
	
	function titleField () {
		return [
			'name' => 'title',
			'label' => 'Title',
			'required' => true,
			'display' => 'InputText'			
		];
	}

	function bodyField () {
		return [
			'name' => 'body',
			'display' => 'Ckeditor'	
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
				return $this->db->distinct('pages', 'tags');
			}
		];
	}

	function categoriesField () {
		return array(
			'name'		=> 'categories',
			'label'		=> 'Category',
			'required'	=> false,
			'options'	=> function () {
				return $this->db->fetchAllGrouped(
					$this->db->collection('categories')->
						find(['section' => 'Pages'])->
						sort(['title' => 1]),
					'_id', 
					'title');
			},
			'display'	=> 'InputToTags',
			'controlled' => true,
			'multiple' => true
		);
	}

	public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
               {{#if pages}}
		                {{#CollectionPagination}}{{/CollectionPagination}}
		                {{#CollectionButtons}}{{/CollectionButtons}}
		                
		                <table class="ui large table segment manager sortable">
		                    <col width="60%">
		                    <col width="20%">
		                    <col width="20%">
		                    <thead>
		                        <tr>
		                            
		                            <th>Title</th>
		                            <th>Status</th>
		                            <th class="trash">Delete</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        {{#each pages}}
		                            <tr data-id="{{dbURI}}">
		                               
		                                <td>{{title}}</td>
		                                <td>{{#Capitalize}}{{status}}{{/Capitalize}}</td>
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
	                         {{#FieldLeft body Body}}{{/FieldLeft}}
	                         {{{id}}}
	                     {{/DocumentFormLeft}}                 
	                
	                     {{#DocumentFormRight}}
	                	    {{#DocumentButton}}{{/DocumentButton}}
		                    {{#FieldFull status}}{{/FieldFull}}
		                    <br />
		                    {{#FieldFull categories Categories}}{{/FieldFull}}
		                	{{#FieldFull tags Tags}}{{/FieldFull}}
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