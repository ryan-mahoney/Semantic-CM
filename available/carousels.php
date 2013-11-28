<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/carousels.php
 * @mode upgrade
 */
namespace Manager;

class carousels {
	private $field = false;
    public $collection = 'carousels';
    public $form = 'carousel';
    public $title = 'Carousel';
    public $singular = 'Carousel';
    public $description = '4 carousels';
    public $definition = '...';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'sign in';
    public $category = 'Content';
    public $notice = 'Carousel Saved';
    public $storage = [
        'collection' => 'carousels',
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
	
	function imageField () {
		return array(
			'name' => 'image',
			'label' => 'Featured Image',
			'display' => VCPF\Field::inputFile(),
			'tooltip' => 'An image that will be displayed when the entry is listed.'
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
	
	function descriptionField () {
		return array(
			'name' => 'description',
			'label' => 'Description',
			'display' => VCPF\Field::textarea(),
			'tooltip' => 'A description that will be displayed when the entry is listed.'
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
                            <th>URL</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each carousels}}
                            <tr data-id="{{dbURI}}">
                                <td>{{label}}</td>
                                <td>{{url}}</td>
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
        	{{#Form}}{{/Form}}
	            <div class="top-container">
	                {{#DocumentHeader}}{{/DocumentHeader}}
	                {{#DocumentTabs}}{{/DocumentTabs}}
	            </div>

	            <div class="bottom-container">
	                {{#DocumentFormLeft}}
	                    {{#FieldLeft title Title required}}{{/FieldLeft}}
	                    {{#FieldLeft url URL required}}{{/FieldLeft}}
	                    {{#FieldEmbedded link menu_links}}{{/FieldEmbedded}}
	                    {{{id}}}
	                {{/DocumentFormLeft}}                 
	                
	                {{#DocumentFormRight}}
	                	{{#DocumentButton}}{{/DocumentButton}}
	                {{/DocumentFormRight}}
	            </div>
	        </form>
HBS;
        return $partial;
    }
}	