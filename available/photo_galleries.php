<?php
/*
 * @version .3
 * @link https://raw.github.com/virtuecenter/manager/master/available/photo_galleries.php
 * @mode upgrade
 */
namespace Manager;

class photo_galleries {
	private $field = false;
    public $collection = 'photo_galleries';
    public $title = 'Photo Galleries';
    public $titleField = 'title';
    public $singular = 'Photo Gallery';
    public $description = '4 photo galleries';
    public $definition = 'Photos organizes into individual galleries.';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Flickr', 'SEO'];
    public $icon = 'photo';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
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
			'display'=>'InputText'
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
		return [
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
    
	public function image_individualField() {
		return [
			'name'		=> 'image_individual',
			'label'		=> 'Images',
			'required'	=> false,
			'display'	=> 'Manager',
			'manager'	=> 'subimages'
		];
	}

	public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
            	{{#if photo_galleries}}
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
	                                <td>{{#ImageResize}}{{image}}{{/ImageResize}}</td>
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
	                        {{#FieldLeft description Summary}}{{/FieldLeft}}
	                        {{#FieldLeft image "Featured Image"}}{{/FieldLeft}}
	                        {{#FieldEmbedded image_individual subimages Images}}{{/FieldEmbedded}}
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
	                <div class="ui tab" data-tab="Flickr">
	                	{{#DocumentFormLeft}}
		                	{{#FieldLeft flicker URL}}{{/FieldLeft}}
		               	{{/DocumentFormLeft}}                 
	                    {{#DocumentFormRight}}{{/DocumentFormRight}}
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