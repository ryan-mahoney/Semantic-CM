<?php
/*
 * @version .3
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/users.php
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
    public $tabs = ['Edit Profile', 'Records'];
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
            'required'    => false,
            'display' => 'InputText'
        ];
    }

    function first_nameField () {
        return [
            'name'        => 'first_name',
            'required'    => true,
            'display' => 'InputText'
        ];
    }

    public function middle_nameField () {
        return [
            'name'    => 'middle_name',
            'required'  => false,
            'display' => 'InputText'
        ];
    }

    public function last_nameField () {
        return [
            'name'    => 'last_name',
            'required'  => true,
            'display' => 'InputText'
        ];
    }

    public function suffixField () {
        return [
            'name'    => 'suffix',
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
            'required'  => false,
            'display' => 'InputText'
        ];
    }

    public function password2Field () {
        return [
            'name'    => 'password2',
            'required'  => false,
            'display' => 'InputPassword'
        ];
    }

    public function organizationField () {
        return [
            'name'    => 'organization',
            'label'   => 'Organization',
            'required'  => false,
            'options' => function () {
                return $this->db->fetchAllGrouped($this->db->collection('organization')->find()->sort(['title' => 1]), '_id', 'title');
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
                return $this->db->fetchAllGrouped($this->db->collection('groups')->find()->sort(['title' => 1]), 'title', 'title');
            },
            'display'   => 'InputToTags',
            'controlled' => true,
            'multiple' => true
        );
    }

    public function users_addressField() {
        return [
            'name' => 'address_sub',
            'label' => 'Address',
            'required' => false,
            'display'   =>  'Manager',
            'manager'   => 'users_address'
        ];
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
                                <th>Last Name</th>
                                <th class="trash">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{#each users}}
                                <tr data-id="{{dbURI}}">
                                    <td>{{first_name}}</td>
                                    <td>{{last_name}}</td>
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
                    <div class="ui tab active" data-tab="Edit Profile">
                        {{#DocumentFormLeft}}
                            {{#FieldLeft prefix Prefix}}
                            {{#FieldLeft first_name "First Name"}}
                            {{#FieldLeft middle_name "Middle Name"}}
                            {{#FieldLeft last_name "Last Name"}}
                            {{#FieldLeft suffix Suffix}}
                            {{#FieldLeft email Email}}
                            {{#FieldLeft phone Phone}}
                            {{#FieldLeft homepage Homepage}}
                            {{#FieldLeft organization Organization}}
                            {{#FieldLeft image Image}}
                            {{{id}}}
                        {{/DocumentFormLeft}}                 
                        
                        {{#DocumentFormRight}}
                            {{#DocumentButton}}
                            {{#FieldFull password2 Password}}
                            {{#FieldFull groups}}
                            {{#FieldFull classification_tags}}
                            <div class="ui clearing divider"></div>
                            {{#FieldLeft point_person}}
                            <br />
                            {{#FieldLeft unsuscribe}}
                            {{#FieldLeft permanent_bounce}}
                            {{#FieldLeft email_complaint}}
                        {{/DocumentFormRight}}
                    </div>          
                </div>
            </form>
HBS;
        return $partial;
    }
}