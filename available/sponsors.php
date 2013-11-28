<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/sponsors.php
 * @mode upgrade
 */
namespace Manager;

class sponsors {
	private $field = false;
    public $collection = 'sponsors';
    public $form = 'sponsors';
    public $title = 'Sponsors';
    public $singular = 'Sponsor';
    public $description = '4 sponsors';
    public $definition = '...';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images'];
    public $icon = 'us dollar';
    public $category = 'Content';
    public $notice = 'Sponsor Saved';
    public $storage = [
        'collection' => 'sponsors',
        'key' => '_id'
    ];
	
	
    function titleField() {
		return [
			'name'		=> 'title',
			'label'		=> 'Title',
			'required'	=>	true,
			'display' => 'InputText'
		];
	}

	function descriptionField() {
		return [
			'name'		=> 'description',
			'label'		=> 'Description',
			'required'	=>	false,
			'display' => 'Textarea'
		];
	}

    function urlField() {
		return [
			'name'		=> 'url',
			'label'		=> 'URL',
			'required'	=>	true,
			'display' => 'InputText'
		];
	}

	public function targetField(){
		return [
			'name'		=>'target',
			'label'		=> 'Target',
			'required'	=> false,
			'options'	=> array(
					'_blank'		=>'New Window',
					'_self'		=>'Self',
					'_parent'	=>'Parent',
					'_top'		=>'Top'
		),
				'display'	=>'Select',
				'default'=> 'self'	
	
		];
	}

	function imageField () {
		return [
				'name' => 'image',
				'label' => 'Logo',
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
	/*
	function code_nameField () {
		return VCPF\DOMFormTableArray::codename('title', 'sponsors');
	}

	function added_dateField() {
		return array(
			'name'			=> 'added_date',
			'label'			=> 'Added Date',
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
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each sponsors}}
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
	        {{#Form}}{{/Form}}
	            <div class="top-container">
	                {{#DocumentHeader}}{{/DocumentHeader}}
	                {{#DocumentTabs}}{{/DocumentTabs}}
	            </div>

	            <div class="bottom-container">
	            	<div class="ui tab active" data-tab="Main">
		                {{#DocumentFormLeft}}
		                    {{#FieldLeft title Title required}}{{/FieldLeft}}
		                    {{#FieldLeft description Description}}{{/FieldLeft}}
		                    {{#FieldLeft url URL}}{{/FieldLeft}}
	                        {{#FieldLeft target Target}}{{/FieldLeft}}
		                    {{{id}}}
		                {{/DocumentFormLeft}}                 
		                
		                {{#DocumentFormRight}}
		                	{{#DocumentButton}}{{/DocumentButton}}
		                	{{#FieldFull status}}{{/FieldFull}}
		                	<div class="ui clearing divider"></div>
		                	{{#FieldLeft featured}}{{/FieldLeft}}
		                	{{#FieldFull categories Categories}}{{/FieldFull}}
		                {{/DocumentFormRight}}
		            </div>

		             <div class="ui tab" data-tab="Images">
		                {{#DocumentFormLeft}}
		                    {{#FieldLeft image Logo}}{{/FieldLeft}}
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