<?php

/*
 * @version .3
 * @link https://raw.github.com/virtuecenter/manager/master/available/file_uploads.php
 * @mode upgrade
 */
namespace Manager;

class file_uploads {
	private $field = false;
    public $collection = 'file_uploads';
    public $title = 'File Uploads';
    public $titleField = 'title';
    public $singular = 'File Upload';
    public $description = '4 file uploads';
    public $definition = '....';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main'];
    public $icon = 'cloud upload';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'file_uploads',
        'key' => '_id'
    ];
	
	function imageField () {
		return [
			'name' => 'image',
			'label' => '"File Upload"',
			'display' => 'InputFile'
		];
	}

	public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#if file_uploads}}
                        {{#CollectionPagination}}{{/CollectionPagination}}
                        {{#CollectionButtons}}{{/CollectionButtons}}
                        
                        <table class="ui large table segment manager">
                            <col width="20%">
                            <col width="70%">
                            <col width="10%">
                            <thead>
                                <tr>
                                    
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Feature</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each file_uploads}}
                                    <tr data-id="{{dbURI}}">
                                         
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
	                        {{#FieldLeft image "File Upload" required}}{{/FieldLeft}}
	                        {{{id}}}
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