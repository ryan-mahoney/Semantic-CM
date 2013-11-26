<?php
/*
 * @version .2
 * @link https://raw.github.com/virtuecenter/manager/master/available/books.php
 * @mode upgrade
 */
namespace Manager;

class books {
	private $field = false;
    public $collection = 'books';
    public $form = 'books';
    public $title = 'Books';
    public $singular = 'Book';
    public $description = '4 books';
    public $definition = '....';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'book';
    public $category = 'Content';
    public $notice = 'Book Saved';
    public $storage = [
        'collection' => 'books',
        'key' => '_id'
    ];

    function titleField () {
		return [
			'name'		=> 'title',
			'label'		=> 'Title',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function descriptionField () {
		return [
			'name' => 'description',
			'label' => 'Body',
			'display' => 'Ckeditor'
		];
	}

	function short_descriptionField () {
		return [
			'name' => 'short_description',
			'label' => 'Summary',
		    'display' => 'Textarea'
		];
	}

	function imageField () {
		return [
			'name' => 'image',
			'label' => 'Book Cover Image',
			'display' => 'InputFile'
		];
	}

	function linkField () {
		return [
			'name'		=> 'link',
			'label'		=> 'URL',
			'required'	=> true,
			'display'	=> 'InputText',
		];
	}


	function priceField () {
		return [
			'name'		=> 'price',
			'label'		=> 'Price',
			'required'	=> true,
			'display'	=> 'InputText',
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
	function titleField () {
		return array(
			'name'		=> 'title',
			'label'		=> 'Title',
			'required'	=> true,
			'display'	=> 'InputText',
		);
	}
	
	function code_nameField () {
		return array_merge(
			VCPF\DOMFormTableArray::codename('title', 'books'),
			[
				'path' => '/book/',
				'selector' => '#title-field input',
				'mode' => 'after'
			]
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
			'default' => 'f',
		);
	}

	function statusField () {
		return array(
			'name'		=> 'status',
			'label'		=> false,
			'required'	=> true,
			'options'	=> array(
				'published'	=> 'Published',
				'draft'		=> 'Draft'
			),
			'display'	=> 'InputRadioButton',
			'nullable'	=> false,
			'default'	=> 'published',
		);
	}
	
	function categoriesField () {
		return array(
			'name'		=> 'categories',
			'label'		=> 'Choose a Category',
			'required'	=> false,
			'tooltip'	=> 'Add one or more categories.',
			'options'	=> function () {
				return VCPF\Model::db('categories')->
					find(['section' => 'Books'])->
					sort(array('title' => 1))->
					fetchAllGrouped('_id', 'title');
			},
			'display'	=> VCPF\Field::selectToPill()
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
			'display' => VCPF\Field::inputToTags(),
			'autocomplete' => function () {
				return VCPF\Model::mongoDistinct('books', 'tags');
			},

		);
	}
	
	function created_dateField() {
		return VCPF\DOMFormTableArray::createdDate();
	}
	
	function defaultTable () {
		return array (
			'columns' => array(
				array('title', '30%', 'Title', false),
				array ('status', '20%', 'Status', function ($data) {
					if ($data == 'published') {
						return 'Published';
					}
					return 'Draft';
				}),
				
				array ('featured', '20%', 'Featured', function ($data) {
					if ($data == 't') {
						return 'Yes';
					}
					return 'No';
				}),
					
				array('created_date', '20%', 'Created Date', function ($data) {
					return date('Y-m-d', $data->sec);
				}),	
			),
			'title' => 'Books',
			'link' => 'title',
			'features' => array('delete', 'search', 'add', 'edit', 'pagination', 'sortable')
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
                            <th>Status</th>
                            <th>Feature</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each books}}
                            <tr data-id="{{dbURI}}">
                                <td>{{title}}</td>
                                <td>{{status}}</td>
                                <td>{{featured}}</td>
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
                    {{#FieldLeft description Body}}{{/FieldLeft}}
                    {{#FieldLeft short_description Summary}}{{/FieldLeft}}
                    {{#FieldLeft image Book Cover Image}}{{/FieldLeft}}
                    {{#FieldLeft link URL}}{{/FieldLeft}}
                    {{#FieldLeft price Price}}{{/FieldLeft}}
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
	