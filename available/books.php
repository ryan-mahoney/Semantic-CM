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
	
	function priceField () {
		return array(
			'name'		=> 'price',
			'label'		=> 'Price',
			'required'	=> true,
			'display'	=> 'InputText',
		);
	}
	
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
	
	function linkField () {
		return array(
			'name'		=> 'link',
			'label'		=> 'Link (URL)',
			'required'	=> true,
			'display'	=> 'InputText',
		);
	}
	
	function short_descriptionField () {
		return array(
			'name' => 'short_description',
			'label' => 'Short Description',
			'display' => VCPF\Field::textarea(),
		);
	}
	
	function descriptionField () {
		return array(
			'name' => 'description',
			'label' => 'Description',
			'display' => VCPF\Field::ckeditor(),
		);
	}
	
	function imageField () {
		return array(
			'name' => 'image',
			'label' => 'Image',
			'display' => VCPF\Field::inputFile(),
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
                    {{#FieldLeft url URL required}}{{/FieldLeft}}
                    {{#FieldEmbedded image Image}}{{/FieldEmbedded}}
                    {{{id}}}
                {{/DocumentFormLeft}}                 
                
                {{#DocumentFormRight}}
                {{/DocumentFormRight}}
            </div>
HBS;
        return $partial;
    }
}
	