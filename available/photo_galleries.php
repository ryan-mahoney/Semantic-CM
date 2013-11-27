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

     function flickerField () {
		return [
			'name' => 'flicker',
			'label'=> 'URL',
			'required'=> false,
			'display'=>'InputText',
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
				return $this->db->distinct('photo_galleries', 'tags');
			}
		];
	}
    

/*
	function afterFieldsetUpdate () {
		return function ($admin) {
			$DOM = VCPF\DOMView::getDOM();
			$DOM['#image_individual-field .table-actions']->append('<a class="btn btn-small vcms-panel" data-id="" data-attributes="{\'gallery\':\'' . (string)$admin->activeRecord['_id'] . '\'}" data-mode="save" data-vc__admin="vc\ms\site\admin\ImageBatchAdmin" style="float: right">Upload Batch</a>');
		};
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
                {{#DocumentTabs}}{{/DocumentTabs}}
            </div>

            <div class="bottom-container">
                <div class="ui tab active" data-tab="Main">
                    {{#DocumentFormLeft}}
                        {{#FieldLeft title Title required}}{{/FieldLeft}}
                        {{#FieldLeft description Summary}}{{/FieldLeft}}
                        {{#FieldLeft image Featured Image}}{{/FieldLeft}}
                        {{{id}}}
                    {{/DocumentFormLeft}}                 
                
                    {{#DocumentFormRight}}
                	    {{#DocumentButton}}{{/DocumentButton}}
	                    {{#FieldFull status}}{{/FieldFull}}
	                    <br />
	                    {{#FieldFull display_date}}{{/FieldFull}}
	                    <br />
	                    {{#FieldLeft featured}}{{/FieldLeft}}
	                    <br />
	                    {{#FieldLeft pinned}}{{/FieldLeft}}
	                    <br />
	                	{{#FieldFull tags Tags}}{{/FieldFull}}
                    {{/DocumentFormRight}}
                </div>
                <div class="ui tab" data-tab="Flickr">
                {{#FieldLeft flicker URL}}{{/FieldLeft}}
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
HBS;
        return $partial;
    }
}	