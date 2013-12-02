<?php
/*
 * @version .2
 * @link https://raw.github.com/virtuecenter/manager/master/available/peoples_person.php
 * @mode upgrade
 */
namespace Manager;

class peoples_person {
	private $field = false;
    public $collection = 'peoples_person';
    public $title = 'Person';
    public $titleField = 'Person';
    public $singular = 'Profile';
    public $description = '4 profiles';
    public $definition = '...';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main'];
    public $icon = 'text file';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'people_person',
        'key' => '_id'
    ];

    public function __construct ($field=false) {
        $this->field = $field;
    }
	
	function prefixField () {
		return [
			'name'		=> 'prefix',
			'label'		=> 'prefix',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function first_nameField () {
		return [
			'name'		=> 'first_name',
			'label'		=> '"First Name"',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

  function middle_nameField () {
    return [
      'name'    => 'middle_name',
      'label'   => '"Middle Name"',
      'required'  => true,
      'display' => 'InputText'
    ];
  }

  function last_nameField () {
    return [
      'name'    => 'last_name',
      'label'   => '"Last Name"',
      'required'  => true,
      'display' => 'InputText'
    ];
  }

  function suffixField () {
    return [
      'name'    => 'suffix',
      'label'   => 'suffix',
      'required'  => true,
      'display' => 'InputText'
    ];
  }

  function emailField () {
    return [
      'name'    => 'email',
      'label'   => 'Email',
      'required'  => true,
      'display' => 'InputText'
    ];
  }


  function phoneField () {
    return [
      'name'    => 'phone',
      'label'   => 'Phone',
      'required'  => true,
      'display' => 'InputText'
    ];
  }

	function homepageField () {
		return [
			'display' => 'Ckeditor',
			'name' => 'homepage'
		];
	}

  function titleField () {
    return [
      'name'    => 'title',
      'label'   => 'Title',
      'required'  => false,
      'display' => 'InputText'
    ];
  }

  function categoriesField () {
    return [
      'name'    => 'organization',
      'label'   => 'Organization',
      'required'  => false,
      'options' => function () {
        return $this->db->fetchAllGrouped(
          $this->db->collection('organization')->
            find(['section' => 'Books'])->
            sort(['title' => 1]),
          '_id', 
          'title');
      },
      'display' => 'InputToTags',
      'controlled' => true,
      'multiple' => true
    ];
  }

  function imageField () {
    return [
      'name' => 'image',
      'label' => 'List View',
      'display' => 'InputFile'
    ];
  }

  function classification_tagsField () {
    return [
      'name' => 'classification_tags',
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
        return $this->db->distinct('peoples_person', 'tags');
      }
    ];
  }

  function point_personField () {
    return [
      'name'    => 'point_person',
      'label'   => 'Person',
      'required'  => false,
      'options' => function () {
        return $this->db->fetchAllGrouped(
          $this->db->collection('point_person')->
            find(['section' => 'Books'])->
            sort(['title' => 1]),
          '_id', 
          'title');
      },
      'display' => 'InputToTags',
      'controlled' => true,
      'multiple' => true
    ];
  }

  function unsuscribeField () {
        return [
            'name' => 'unsuscribe',
            'label' => 'Unsuscribe',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

     function permanent_bounceField () {
        return [
            'name' => 'permanent_bounce',
            'label' => 'Permanent',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

     function email_complaintField () {
        return [
            'name' => 'email_complaint',
            'label' => 'Complain',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }


    public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

           <div class="bottom-container">
              {{#if peoples_person}}
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
                            {{#each peoples_person}}
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
		                    {{#FieldLeft prefix Prefix}}{{/FieldLeft}}
		                    {{#FieldLeft first_name "First Name"}}{{/FieldLeft}}
		                    {{#FieldLeft middle_name "Middle Name"}}{{/FieldLeft}}
		                    {{#FieldLeft last_name "Last Name"}}{{/FieldLeft}}
		                    {{#FieldLeft suffix Suffix}}{{/FieldLeft}}
                        {{#FieldLeft email Email}}{{/FieldLeft}}
                        {{#FieldLeft phone Phone}}{{/FieldLeft}}
                        {{#FieldLeft homepage Homepage}}{{/FieldLeft}}
                        {{#FieldLeft organization Organization}}{{/FieldLeft}}
                        {{#FieldLeft image Image}}{{/FieldLeft}}
		                    {{{id}}}
		                {{/DocumentFormLeft}}                 
		                
		                {{#DocumentFormRight}}
		                	{{#DocumentButton}}{{/DocumentButton}}
		                	{{#FieldFull classification_tags}}{{/FieldFull}}
		                	<div class="ui clearing divider"></div>
		                	{{#FieldLeft point_person}}{{/FieldLeft}}
		                	<br />
		                	{{#FieldLeft unsuscribe Unsuscribe}}{{/FieldLeft}}
                      {{#FieldLeft permanent_bounce Permanent}}{{/FieldLeft}}
                      {{#FieldLeft email_complaint Complain}}{{/FieldLeft}}
		                {{/DocumentFormRight}}
		            </div>	        
	            </div>
	        </form>
HBS;
        return $partial;
    }
}	 