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

/*
	function code_nameField () {
		return array_merge(
			VCPF\DOMFormTableArray::codename('title', 'blogs'),
			[
				'path' => '/blog/',
				'selector' => '#title-field input',
				'mode' => 'after' 
			]
		);
	}
*/
	function authorField () {
		return [
			'name'		=> 'author',
			'label'		=> 'Author',
			'required'	=> false,
			'display'	=> 'InputText'
		];
	}
	
	function titleField () {
		return array(
			'name'		=> 'title',
			'label'		=> 'Title',
			'required'	=> true,
			'display'	=> 'InputText',
			'tooltip'	=> 'The title that will be displayed for this post.'
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
			'tooltip'	=> 'Published entries show up on the website.'
		);
	}

	function commentsField () {
		return array(
			'name' => 'comments',
			'label' => false,
			'required' => false,
			'options' => array(
				't' => 'Yes',
				'f' => 'No'
			),
			'display' => 'InputRadioButton',
			'default' => 't',
			'tooltip' => 'Enable comments for this entry?'
		);
	}
	
	function pinnedField () {
		return array(
			'name' => 'pinned',
			'label' => false,
			'required' => false,
			'options' => array(
				't' => 'Yes',
				'f' => 'No'
			),
			'display' => 'InputRadioButton',
			'default' => 'f',
			'tooltip' => 'Pin this entry?'
		);
	}
	
	function dateField() {
		return array(
			'name'			=> 'display_date',
			'label'			=> 'Display Date',
			'required'		=> true,
			'display'		=> 'InputDatePicker',
			'tooltip'		=> 'Helpful for back-dating and scheduling future posts.',
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
			'display'	=> VCPF\Field::selectToPill()
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
			'display'		=> VCPF\Field::selectToPill()
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
				return VCPF\Model::mongoDistinct('blogs', 'tags');
			},
			'tooltip' => 'Another way to make entries more findable.'
		);
	}

	function bodyField () {
		return [
			'display' => VCPF\Field::ckeditor(),
			'name' => 'body',
			'label' => 'Article Body',
			'addTooltip' => 'Click here to add additional content like Youtube videos and Flickr galleries.',
			'addLabel' => 'Add More Content'
		];
	}

	function descriptionField () {
		return array(
			'name' => 'description',
			'label' => 'Summary',
			'display' => VCPF\Field::textarea(),
			'tooltip' => 'A summary that will be displayed when the entry is listed.'
		);
	}

	function imageField () {
		return array(
			'name' => 'image',
			'label' => 'Image',
			'display' => VCPF\Field::inputFile(),
			'tooltip' => 'An image that will be displayed when the entry is listed.'
		);
	}
	
	function description1Field () {
		return array(
			'name' => 'description1',
			'label' => 'Summary',
			'display' => VCPF\Field::textarea(),
			'tooltip' => 'A summary that will be displayed when the entry is listed.'
		);
	}

	function image1Field () {
		return array(
			'name' => 'image1',
			'label' => 'Image',
			'display' => VCPF\Field::inputFile(),
			'tooltip' => 'An image that will be displayed when the entry is listed.'
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
			'tooltip' => 'Make this entry featured on the website?'
		);
	}
	
	function publication_nameField () {
		return array(
			'name'		=> 'publication_name',
			'label'		=> 'Publication Name',
			'required'	=> false,
			'display'	=> 'InputText',

		);
	}
	
	function linkField () {
		return array(
			'name'		=> 'link',
			'label'		=> 'Link',
			'required'	=> false,
			'display'	=> 'InputText',

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