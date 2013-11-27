<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/pages.php
 * @mode upgrade
 */
namespace Manager;

class pages {
	private $field = false;
    public $collection = 'pages';
    public $form = 'pages';
    public $title = 'Pages';
    public $singular = 'Page';
    public $description = '4 pages';
    public $definition = '...';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'SEO'];
    public $icon = 'copy';
    public $category = 'Content';
    public $notice = 'Page Saved';
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
	
/*
	function created_dateField() {
		return VCPF\DOMFormTableArray::createdDate();
	}

	function code_nameField () {
		return array_merge(
			VCPF\DOMFormTableArray::codename('title', 'pages'),
			[
				'path' => '/page/',
				'selector' => '#title-field input',
				'mode' => 'after'
			]
		);
	}
	
	function categoryField () {
		return array(
			'name'		=> 'category',
			'label'		=> 'Category',
			'required'	=> false,
			'tooltip'	=> 'Add a category.',
			'options'	=> function () {
				return VCPF\Model::db('categories')->
					find(['section' => 'Pages'])->
					sort(array('title' => 1))->
					fetchAllGrouped('_id', 'title');
			},
			'display'	=> VCPF\Field::select(),
			'nullable'	=> true
		);
	}	

	function defaultTable () {
		return array (
			'columns' => array(				
				array('title', '30%', 'Title', false),
				array('category', '25%', 'Category', function ($data) {
					if (empty($data)) {
						return;
					}
					if (PagesAdmin::$categories === false && PagesAdmin::$categoriesTried === false) {
						PagesAdmin::$categoriesTried = true;
						PagesAdmin::$categories = VCPF\Model::db('categories')->
							find(['section' => 'Pages'], ['_id', 'title'])->
							fetchAllGrouped('_id', 'title');
					}					
					if (isset(PagesAdmin::$categories[$data])) {
						return PagesAdmin::$categories[$data];
					}
				}),
				['created_date', '25%', 'Created Date', function ($data) {
					if (empty($data)) {
						 return '';
					}
					return date('m/d/Y', $data->sec);
				}],
			),
			'title' => 'Pages',
			'link' => 'title',
			'features' => array('delete', 'search', 'add', 'edit', 'pagination', 'sortable'),
			'sort' => array('created_date' => -1)
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
                    <col width="20%">
                    <col width="70%">
                    <col width="10%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each pages}}
                            <tr data-id="{{dbURI}}">
                                <td>{{title}}</td>
                                <td>{{category}}</td>
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
                    {{#FieldLeft body Body}}{{/FieldLeft}}
                    {{{id}}}
                {{/DocumentFormLeft}}                 
                
                {{#DocumentFormRight}}
                {{#FieldFull status}}{{/FieldFull}}
                <br />
                {{/DocumentFormRight}}
            </div>
HBS;
        return $partial;
    }
}