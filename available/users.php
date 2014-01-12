<?php
/*
 * @version .3
 * @link https://raw.github.com/virtuecenter/manager/master/available/users.php
 * @mode upgrade
 * .3 definition and description for count added
 */
namespace Manager;

class users {
    private $field = false;
    public $collection = 'users';
    public $title = 'People';
    public $titleField = 'first_name';
    public $singular = 'Person';
    public $description = '{{count}} people';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main'];
    public $icon = 'text file';
    public $category = 'People';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'users',
        'key' => '_id'
    ];

    public function __construct ($field=false) {
        $this->field = $field;
    }
    
    function prefixField () {
        return [
            'name'        => 'prefix',
            'label'       => 'prefix',
            'required'    => false,
            'display' => 'InputText'
        ];
    }

      function first_nameField () {
            return [
                  'name'        => 'first_name',
                  'label'       => '"First Name"',
                  'required'    => true,
                  'display' => 'InputText'
            ];
      }

    public function middle_nameField () {
        return [
            'name'    => 'middle_name',
            'label'   => '"Middle Name"',
            'required'  => false,
            'display' => 'InputText'
        ];
    }

    public function last_nameField () {
        return [
          'name'    => 'last_name',
          'label'   => '"Last Name"',
          'required'  => true,
          'display' => 'InputText'
        ];
    }

    public function suffixField () {
        return [
            'name'    => 'suffix',
            'label'   => 'suffix',
            'required'  => false,
            'display' => 'InputText'
        ];
    }

    public function emailField () {
        return [
            'name'    => 'email',
            'label'   => 'Email',
            'required'  => true,
            'display' => 'InputText'
        ];
    }

    public function phoneField () {
        return [
            'name'    => 'phone',
            'label'   => 'Phone',
            'required'  => false,
            'display' => 'InputText'
        ];
    }

      function homepageField () {
            return [
                  'display' => 'InputText',
                  'name' => 'homepage'
            ];
      }

    public function titleField () {
        return [
            'name'    => 'title',
            'label'   => 'Title',
            'required'  => false,
            'display' => 'InputText'
        ];
    }

    public function categoriesField () {
        return [
            'name'    => 'organization',
            'label'   => 'Organization',
            'required'  => false,
            'options' => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('organization')->find(['section' => 'Books'])->sort(['title' => 1]), '_id', 'title');
            },
            'display' => 'InputToTags',
            'controlled' => true,
            'multiple' => true
        ];
    }

    public function imageField () {
        return [
            'name' => 'image',
            'label' => 'List View',
            'display' => 'InputFile'
       ];
    }

    public function classification_tagsField () {
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
                 return $this->db->distinct('users', 'tags');
            }
        ];
    }

    public function point_personField () {
        return [
            'name'    => 'point_person',
            'label'   => 'Person',
            'required'  => false,
            'options' => function () {
                return $this->db->fetchAllGrouped($this->db->collection('users')->find(['groups' => 'crm'])->sort(['title' => 1]), '_id', 'title');
            },
            'display' => 'InputToTags',
            'controlled' => true,
            'multiple' => true
        ];
    }

    public function unsuscribeField () {
        return [
            'name' => 'unsuscribe',
            'label' => 'Unsuscribe',
            'required' => false,
            'options' => [
                't' => 'Yes',
                'f' => 'No'
            ],
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

    public function permanent_bounceField () {
        return [
            'name' => 'permanent_bounce',
            'label' => 'Permanent',
            'required' => false,
            'options' => [
                't' => 'Yes',
                'f' => 'No'
            ],
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

    public function email_complaintField () {
        return [
            'name' => 'email_complaint',
            'label' => 'Complain',
            'required' => false,
            'options' => [
                't' => 'Yes',
                'f' => 'No'
            ],
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

    function groupsField () {
        return array(
            'name'      => 'groups',
            'required'  => false,
            'options'   => function () {
                return $this->db->fetchAllGrouped($this->db->collection('groups')->find()->sort(['title' => 1]), '_id', 'title');
            },
            'display'   => 'InputToTags',
            'controlled' => true,
            'multiple' => true
        );
    }
    
    public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}
            </div>

           <div class="bottom-container">
              {{#if users}}
                    {{#CollectionPagination}}
                    {{#CollectionButtons}}
                
                    <table class="ui large table segment manager">
                         <thead>
                               <tr>
                                  <th>First Name</th>
                                  <th>Category</th>
                                  <th>Status</th>
                                  <th class="trash">Delete</th>
                               </tr>
                         </thead>
                         <tbody>
                            {{#each users}}
                                 <tr data-id="{{dbURI}}">
                                     <td>{{first_name}}</td>
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
                     {{#CollectionPagination}}
                {{else}}
                     {{#CollectionEmpty}}
              {{/if}}
           </div>
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            {{#Form}}
                <div class="top-container">
                    {{#DocumentHeader}}
                    {{#DocumentTabs}}
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
                            {{#DocumentButton}}
                            GROUPS...
                            {{#FieldFull groups}}{{/FieldFull}}
                            {{#FieldFull classification_tags}}{{/FieldFull}}
                            <div class="ui clearing divider"></div>
                            {{#FieldLeft point_person}}{{/FieldLeft}}
                            <br />
                            {{#FieldLeft unsuscribe}}{{/FieldLeft}}
                            {{#FieldLeft permanent_bounce}}{{/FieldLeft}}
                            {{#FieldLeft email_complaint}}{{/FieldLeft}}
                        {{/DocumentFormRight}}
                    </div>          
                </div>
            </form>
HBS;
        return $partial;
    }
}