<?php

/*
 * @version .2
 * @link https://raw.github.com/virtuecenter/manager/master/available/videos.php
 * @mode upgrade
 */
namespace Manager;

class videos {
	private $field = false;
    public $collection = 'videos';
    public $form = 'videos';
    public $title = 'Videos';
    public $singular = 'Video';
    public $description = '4 videos';
    public $definition = '....';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'SEO'];
    public $icon = 'facetime video';
    public $category = 'Content';
    public $notice = 'Video Saved';
    public $storage = [
        'collection' => 'videos',
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

	function descriptionField(){
		return [
			'name'=>'description',
			'label'=>'Summary',
			'required'=>false,
			'display' => 'Textarea'
		];
	}

	function imageField () {
		return [
			'name' => 'image',
			'label' => 'Featured Image',
			'display' => 'InputFile'
		];
	}

	function videoField () {
		return [
			'name' => 'video',
			'label' => 'URL',
			'required' => true,
			'display' => 'InputText'
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

	function categoriesField () {
		return array(
			'name'		=> 'categories',
			'label'		=> 'Category',
			'required'	=> false,
			'options'	=> function () {
				return $this->db->fetchAllGrouped(
					$this->db->collection('categories')->
						find(['section' => 'Blog'])->
						sort(['title' => 1]),
					'_id', 
					'title');
			},
			'display'	=> 'InputToTags',
			'controlled' => true,
			'multiple' => true
		);
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
				return $this->db->distinct('blogs', 'tags');
			}
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
				find(['section' => 'Videos'])->
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
				return VCPF\Model::mongoDistinct('videos', 'tags');
			},
			'tooltip' => 'Another way to make entries more findable.'
		);
	}
	
	function dateField() {
		return array(
			'name'			=> 'display_date',
			'label'			=> 'Display Date',
			'required'		=> true,
			'display'		=> VCPF\Field::inputDatePicker(),
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
                            <th>Video</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Feature</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each videos}}
                            <tr data-id="{{dbURI}}">
                                 <td>{{video}}</td>
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
                        {{#FieldLeft video URL}}{{/FieldLeft}}
                        {{{id}}}
                    {{/DocumentFormLeft}}                 
                
                    {{#DocumentFormRight}}
	                    {{#DocumentButton}}{{/DocumentButton}}
	                    {{#FieldFull status}}{{/FieldFull}}
	                    <br />
	                    {{#FieldFull display_date}}{{/FieldFull}}
	                     <div class="ui clearing divider"></div>
	                    {{#FieldLeft featured}}{{/FieldLeft}}
	                    <br />
	                    {{#FieldLeft pinned}}{{/FieldLeft}}
	                     <div class="ui clearing divider"></div>
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
HBS;
        return $partial;
    }
}	