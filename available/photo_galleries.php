<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/photo_galleries.php
 * @mode upgrade
 */
namespace Manager;

class photo_galleries {
	private $field = false;
    public $collection = 'photo_galleries';
    public $form = 'photo_galleries';
    public $title = 'Photo Galleries';
    public $singular = 'Photo Gallery';
    public $description = '4 photo_galleries';
    public $definition = '...';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Flickr', 'SEO'];
    public $icon = 'photo';
    public $category = 'Content';
    public $notice = 'photo_gallery Saved';
    public $storage = [
        'collection' => 'photo_galleries',
        'key' => '_id'
    ];
	

	function titleField () {	
		return [
			'name' => 'title',
			'label' => 'Title',
			'required' => true,
			'display' => 'InputText'
		];
	}

	function descriptionField () {
		return [
			'name' => 'description',
			'label' => 'Summary',
			'display' => 'Textarea',
			'tooltip' => 'A description that will be displayed when the entry is listed.'
		];
	}

	function imageField () {
		return [
			'name' => 'image',
			'label' => 'Featured Image',
			'display' => 'InputFile',
			'tooltip' => 'An image that will be displayed when the entry is listed.'
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
    

/*
	function afterFieldsetUpdate () {
		return function ($admin) {
			$DOM = VCPF\DOMView::getDOM();
			$DOM['#image_individual-field .table-actions']->append('<a class="btn btn-small vcms-panel" data-id="" data-attributes="{\'gallery\':\'' . (string)$admin->activeRecord['_id'] . '\'}" data-mode="save" data-vc__admin="vc\ms\site\admin\ImageBatchAdmin" style="float: right">Upload Batch</a>');
		};
	}
	
	function code_nameField () {
		return array_merge(
			VCPF\DOMFormTableArray::codename('title', 'photo_galleries'),
			[
				'path' => '/gallery/',
				'selector' => '#title-field input',
				'mode' => 'after'
			]
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

	 function flickerField () {
		return array(
			'name' => 'flicker',
			'label'=>'Flickr Set URL',
			'required'=> false,
			'display'=>'InputText',
		);
	}	
	
	
	public function image_individualField() {
		return array(
			'name' => 'image_individual',
			'label' => 'Add Individual Image',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> 'vc\ms\site\subdocuments\ImageSubAdmin'
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
			'display' => VCPF\Field::inputRadioButton(),
			'default' => 'f',
		);
	
	}
	
	function display_dateField() {
		return array(
			'name'=> 'display_date',
			'label'=> 'Display Date',
			'required'=>true,
			'display' => VCPF\Field::inputDatePicker(),
			'transformIn' => function ($data) {
				return new \MongoDate(strtotime($data));
			},
			'transformOut' => function ($data) {
				return date('m/d/Y', $data->sec);
			},
			'default' => function () {
				return date('m/d/Y', (strtotime('now')));
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
					find(['section' => 'Galleries'])->
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
				return VCPF\Model::mongoDistinct('photo_galleries', 'tags');
			},
			'tooltip' => 'Another way to make entries more findable.'
		);
	}	
	
	function defaultTable () {
		return array (
			'columns' => [
				['image', '20%', 'Image', function ($data) {
					if (isset($data['url'])) {
						return '<a class="link"><img src="' . ImageResizer::getPath($data['url'], 180, 120,  '3:2') . '" /></a>';
					}
				}],
				['title', '45%', 'Title', false],
				['display_date', '15%', 'Date', function ($data) {
					return date('Y-m-d', $data->sec);
				}],				
				['status', '15%', 'Status', function ($data) {
					if (is_array($data)) {
						return print_r($data, true);
					}
					return strtoupper($data);
				}],	
				['featured','10%', 'Featured', function ($data) {
					if ($data == 't') {
						return 'Yes';
					}
					return 'No';
				}],
				
			],
			'title' => 'Photo Galleries',
			'link' => 'title',
			'sort' => array('display_date' => -1, 'title' => 1),
			'features' => array('delete', 'search', 'add', 'edit', 'pagination')
		);
	}*/
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
                            <th>Image</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Feature</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each photo_galleries}}
                            <tr data-id="{{dbURI}}">
                                <td>{{image}}</td>
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
                    {{#FieldLeft description Summary}}{{/FieldLeft}}
                    {{#FieldLeft image Featured Image}}{{/FieldLeft}}
                    {{{id}}}
                {{/DocumentFormLeft}}                 
                
                {{#DocumentFormRight}}
                {{#FieldFull status}}{{/FieldFull}}
                <br />
                {{#FieldFull display_date}}{{/FieldFull}}
                <br />
                {{#FieldLeft featured}}{{/FieldLeft}}
                <br />
                {{#FieldLeft pinned}}{{/FieldLeft}}
                {{/DocumentFormRight}}
            </div>
HBS;
        return $partial;
    }
}	