<?php
/*
 * @version .3
 * @link https://raw.github.com/virtuecenter/manager/master/available/departments.php
 * @mode upgrade
 * .3 definition and description for count added
 */

namespace Manager;


class departments{
	private $field = false;
    public $collection = 'departments';
    public $title = 'Departments';
    public $titleField = 'title';
    public $singular = 'Department';
    public $description = '{{cout}} departments';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'departments',
        'key' => '_id'
    ];

	function titleField () {
		return array(
			'name' => 'title',
			'label' => 'Title',
			'required' => true,
			'display' => 'InputText'
		);
	}

	function descriptionField () {
		return [
			'name' => 'description',
			'label' => 'Summary',
			'display' => 'Textarea'
		];
	}

	public function department_profilesField() {
		return [
			'name' => 'department_profiles',
			'label' => 'Department Profiles',
			'required' => false,
			'display'	=>	'Manager',
			'manager'	=> 'department_profiles'
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
	
	function templateField () {
		return [
			'name' => 'template',
			'label' => 'Type',
			'required' => true,
			'options' => function () {
				$templates = VCPF\Config::category()['templates'];
				if (!is_array($templates) || count($templates) == 0) {
					$templates = ['__vc__ms__site__admin__CategoryAdmin' => 'Basic'];
				}
				return $templates;
			},
			'display' => VCPF\Field::select(),
			//'nullable' => 'Choose a Template'
		];
	}
	
*/

	public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#if departments}}
		                {{#CollectionPagination}}{{/CollectionPagination}}
		                {{#CollectionButtons}}{{/CollectionButtons}}
		                
		                <table class="ui large table segment manager">
		                    <thead>
		                        <tr>
		                            <th>Title</th>
		                            <th>Section</th>
		                            <th class="trash">Delete</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        {{#each departments}}
		                            <tr data-id="{{dbURI}}">
		                                <td>{{title}}</td>
		                                <td>{{section}}</td>
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
	                        {{#FieldLeft description Description}}{{/FieldLeft}}
						    {{#FieldEmbedded profiles Profiles}}{{/FieldEmbedded}}
	                        {{{id}}}
	                    {{/DocumentFormLeft}}                 
	                
	                    {{#DocumentFormRight}}
	                	    {{#DocumentButton}}{{/DocumentButton}}
	                	    {{#FieldLeft featured}}{{/FieldLeft}}
		                	<br />
		                	{{#FieldLeft pinned}}{{/FieldLeft}}
	                    {{/DocumentFormRight}}
	                </div>
		        </div>
		    </form>
HBS;
        return $partial;
    }
}