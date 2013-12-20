<?php
/*
 * @version .8
 * @link https://raw.github.com/virtuecenter/manager/master/available/publications.php
 * @mode upgrade
 *
 * .6 add categories to list
 * .7 typo
 * .8 definition and description for count added
 */
namespace Manager;

class publications {
	private $field = false;
    public $collection = 'publications';
    public $title = 'Publications';
    public $titleField = 'title';
    public $singular = 'Publication';
    public $description = '{{count}} publications';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'SEO'];
    public $icon = 'text file';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'publications',
        'key' => '_id'
    ];

    public function __construct ($field=false) {
        $this->field = $field;
    }
	
	function titleField () {
		return [
			'name'		=> 'title',
			'label'		=> 'Title',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

    function imageField () {
        return [
            'name' => 'image',
            'label' => 'File Upload',
            'display' => 'InputFile'
        ];
    }

	function date_publishedField() {
		return [
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
		];
	}

    public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

           <div class="bottom-container">
              {{#if publications}}
                    {{#CollectionPagination}}{{/CollectionPagination}}
                    {{#CollectionButtons}}{{/CollectionButtons}}
                
                    <table class="ui large table segment manager sortable">
                        <col width="90%">
                        <col width="10%">
                        <thead>
                               <tr>
                                  <th>Title</th>
                                  <th class="trash">Delete</th>
                               </tr>
                        </thead>
                        <tbody>
                            {{#each publications}}
                                <tr data-id="{{dbURI}}">
                                    <td>{{title}}</td>
                                    <td>
                                       <div class="manager trash ui icon button"><i class="trash icon"></i></div>
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
                            {{#FieldLeft image "File Upload" required}}{{/FieldLeft}}
		                    {{{id}}}
		                {{/DocumentFormLeft}}                 
		                
		                {{#DocumentFormRight}}
		                	{{#DocumentButton}}{{/DocumentButton}}
		                	{{#FieldFull status}}{{/FieldFull}}
		                	<br />
		                	{{#FieldFull date_published}}{{/FieldFull}}
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