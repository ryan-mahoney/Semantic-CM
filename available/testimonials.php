<?php
/*
 * @version .1
 * @link https://raw.github.com/virtuecenter/manager/master/available/testimonials.php
 * @mode upgrade
 */
namespace Manager;

class testimonials {
	private $field = false;
    public $collection = 'testimonials';
    public $form = 'testimonials';
    public $title = 'Testimonials';
    public $singular = 'Testimonial';
    public $description = '4 testimonials';
    public $definition = '...';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images'];
    public $icon = 'chat';
    public $category = 'Content';
    public $notice = 'Testimonials Saved';
    public $storage = [
        'collection' => 'testimonials',
        'key' => '_id'
    ];

    function locationField(){
		return [
				'name'=>'location',
				'label'=>'Location',
				'display'	=> 'InputText'
		];
	}
	
	function occupationField(){
		return array(
			'name'=>'occupation',
			'label'=>'Occupation',
			'display'	=> 'InputText'
		);
	}	


	function messageField(){
		return [
			'name'=>'message',
			'label'=>'Message',
			'display' => 'Ckeditor',
			];
	}

	function messageshortField(){
		return [
			'name'=>'messageshort',
			'label'=>'"Short Message"',
			'display' => 'Ckeditor',
		];
	}


	function imageField () {
		return [
			'name' => 'image',
			'label' => 'Image',
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

    function approvedField () {
		return [
			'name' => 'approved',
			'label' => false,
			'required' => false,
			'options' => array(
				't' => 'Yes',
				'f' => 'No'
		),
			'display' => 'InputRadioButton',
			'default' => 'f'
		];
	}

	/*
	function code_nameField () {
		return VCPF\DOMFormTableArray::codename('name', 'testimonials');
	}
	

	function nameField(){
		return array(
			'name'=>'name',
			'label'=>'Name',
			'display'=>VCPF\Field::inputText(),
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
                        {{#each testimonials}}
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
		                    {{#FieldLeft location Location required}}{{/FieldLeft}}
		                    {{#FieldLeft occupation Occupation}}{{/FieldLeft}}
		                    {{#FieldLeft message Message}}{{/FieldLeft}}
		                    {{#FieldLeft messageshort "Short Message"}}{{/FieldLeft}}
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
		                	{{#FieldLeft approved}}{{/FieldLeft}}
		                {{/DocumentFormRight}}
		            </div>

		             <div class="ui tab" data-tab="Images">
		                {{#DocumentFormLeft}}
		                    {{#FieldLeft image Image}}{{/FieldLeft}}
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