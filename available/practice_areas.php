<?php
/*
 * @version .4
 * @link https://raw.github.com/virtuecenter/manager/master/available/practice_areas.php
 * @mode upgrade
 *
 * .4 remove sort
 */
namespace Manager;

class practice_areas {
	private $field = false;
    public $collection = 'practice_areas';
    public $title = 'Practice Areas';
    public $titleField = 'title';
    public $singular = 'Practice Area';
    public $description = '4 practice areas';
    public $definition = '......';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'SEO'];
    public $icon = 'legal';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'practice_areas',
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

	function bodyField () {
		return [
			'display' => 'Ckeditor',
			'name' => 'body'
		];
	}

	function descriptionField () {
		return [
			'name' => 'description',
			'label' => 'Summary',
			'display' => 'Textarea'
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

	/*
	function created_dateField() {
		return VCPF\DOMFormTableArray::createdDate();
	}

	function naemField () {
		return array(
			'name' => 'name',
			'label' => 'Name',
			'required' => true,
			'display' => VCPF\Field::inputText()
		);
	}

	function summaryField () {
		return array(
			'name' => 'summary',
			'label' => 'Short Description',
			'required' => false,
			'display' => VCPF\Field::textarea()
		);
	}

	function descriptionField () {
		return [
			'name' => 'description',
			'label' => 'Description',
			'required' => false,
			'display' => VCPF\Field::ckeditor()
		];
	}		
*/
	public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
            	{{#if practice_areas}}
	                {{#CollectionPagination}}{{/CollectionPagination}}
	                {{#CollectionButtons}}{{/CollectionButtons}}
	                
	                <table class="ui large table segment manager sortable">
	                    <col width="60%">
	                    <col width="20%">
	                    <col width="20%">
	                    <thead>
	                        <tr>
	                            
	                            <th>Title</th>
	                            <th>Status</th>
	                            <th class="trash">Delete</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        {{#each practice _areas}}
	                            <tr data-id="{{dbURI}}">
	                               
	                                <td>{{title}}</td>
	                                <td>{{#Capitalize}}{{status}}{{/Capitalize}}</td>
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
	                        {{#FieldLeft body Body}}{{/FieldLeft}}
	                        {{#FieldLeft description Summary}}{{/FieldLeft}}
	                        {{{id}}}
	                    {{/DocumentFormLeft}}                 
	                
	                    {{#DocumentFormRight}}
	                	    {{#DocumentButton}}{{/DocumentButton}}
		                    {{#FieldFull status}}{{/FieldFull}}
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
	        </form>
HBS;
        return $partial;
    }
}	