<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/blogs.php
 * @mode upgrade
 */
namespace Manager;

class blogs {
	private $field = false;
    public $collection = 'blogs';
    public $form = 'blogs';
    public $title = 'Blogs';
    public $singular = 'Blog';
    public $description = '4 blogs';
    public $definition = '...';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images', 'External Article', 'SEO'];
    public $icon = 'text file';
    public $category = 'Content';
    public $notice = 'Blog Saved';
    public $storage = [
        'collection' => 'blogs',
        'key' => '_id'
    ];

    public function __construct ($field=false) {
        $this->field = $field;
    }
	
	function titleField () {
		return [
			'name'		=> 'title',
			'label'		=> 'Title',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function bodyField () {
		return [
			'display' => 'Ckeditor',
			'name' => 'body'
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

    function commentsField () {
        return [
            'name' => 'comments',
            'label' => 'Comments',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

    function pinnedField () {
        return [
            'name' => 'pinned',
            'label' => 'Pin',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

	function dateField() {
		return [
			'name'			=> 'display_date',
			'required'		=> true,
			'display'		=> 'InputDatePicker',
			'transformIn'	=> function ($data) {
				return new \MongoDate(strtotime($data));
			},
			'transformOut'	=> function ($data) {
				return date('m/d/Y', $data->sec);
			},
			'default'		=> function () {
				return date('m/d/Y');
			}
		];
	}

	function descriptionField () {
		return [
			'name' => 'description',
			'label' => 'Summary',
			'display' => 'Textarea'
		];
	}

/*
	function categoriesField () {
		return array(
			'name'		=> 'categories',
			'label'		=> 'Choose a Category',
			'required'	=> false,
			'tooltip'	=> 'Add one or more categories.',
			'options'	=> function () {
				return VCPF\Model::db('categories')->
					find(['section' => 'Blog'])->
					sort(array('title' => 1))->
					fetchAllGrouped('_id', 'title');
			},
			'display'	=> 'SelectToPill'
		);
	}
	
	function authorsField () {
		return array(
			'name'			=> 'authors',
			'label'			=> 'Add an Author',
			'required'		=> false,
			'tooltip'		=> 'Add one or more authors.',
			'options'		=> function () {
				return VCPF\Model::db('users')->
					find(['activated' => true])->
					sort(['first_name' => 1])->
					fetchAllGrouped('_id', ['first_name', 'last_name']);
			},
			'display'		=> 'SelectToPill'
		);
	}

	function tagsField () {
		return array(
			'name' => 'tags',
			'label' => 'Tags',
			'required' => false,
			'transformIn' => function ($data) {
				return VCPF\Regex::csvToArray($data);
			},
			'display' => 'InputToTags',
			'autocomplete' => function () {
				return VCPF\Model::mongoDistinct('blogs', 'tags');
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
	
	function publication_nameField () {
		return array(
			'name'		=> 'publication_name',
			'label'		=> 'Publication Name',
			'required'	=> false,
			'display'	=> 'InputText'
		);
	}
	
	function linkField () {
		return array(
			'name'		=> 'link',
			'label'		=> 'Link',
			'required'	=> false,
			'display'	=> 'InputText'
		);
	}
	
	function date_publishedField() {
		return array(
			'name'			=> 'date_published',
			'label'			=> 'Date Published',
			'required'		=> false,
			'display'		=> 'InputDatePicker',
			'transformIn'	=> function ($data) {
				return new \MongoDate(strtotime($data));
			},
			'transformOut'	=> function ($data) {
				return date('m/d/Y', $data->sec);
			},
			'default'		=> function () {
				return date('m/d/Y');
			}
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
                            <th>Category</th>
                            <th>Status</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each blogs}}
                            <tr data-id="{{dbURI}}">
                                <td>{{title}}</td>
                                <td>{{category}}</td>
                                <td>{{status}}</td>
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
                {{#DocumentTabs}}{{/DocumentTabs}}
            </div>

            <div class="bottom-container">
            	<div class="ui tab active" data-tab="Main">
	                {{#DocumentFormLeft}}
	                    {{#FieldLeft title Title required}}{{/FieldLeft}}
	                    {{#FieldLeft body Body}}{{/FieldLeft}}
	                    {{#FieldLeft description Summary}}{{/FieldLeft}}
	                    {{{id}}}
	                {{/DocumentFormLeft}}                 
	                
	                {{#DocumentFormRight}}
	                	{{#DocumentButton}}{{/DocumentButton}}
	                	{{#FieldFull status}}{{/FieldFull}}
	                	<br />
	                	{{#FieldFull display_date}}{{/FieldFull}}
	                	<br />
	                	<hr />
	                	<br />
	                	{{#FieldLeft featured}}{{/FieldLeft}}
	                	<br />
	                	{{#FieldLeft pinned}}{{/FieldLeft}}
	                	<br />
	                	{{#FieldLeft comments}}{{/FieldLeft}}
	                	
	                {{/DocumentFormRight}}
	            </div>
	            <div class="ui tab" data-tab="SEO">
	            	SEO
	            </div>
            </div>
HBS;
        return $partial;
    }
}	