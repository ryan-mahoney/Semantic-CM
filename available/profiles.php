<?php
/*
 * @version .3
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/profiles.php
 * @mode upgrade
 * .3 definition and description for count added
 */
namespace Manager;

class profiles {
    private $field = false;
    public $collection = 'profiles';
    public $title = 'Profiles';
    public $titleField = 'title';
    public $singular = 'Profile';
    public $description = '{{count}} profiles';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images'];
    public $icon = 'text file';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'profiles',
        'key' => '_id'
    ];

    public function __construct ($field=false) {
        $this->field = $field;
    }

    function first_nameField() {
    return [
      'name'    => 'first_name',
      'placeholder' => 'First Name',
      'display' => 'InputText',
      'required'  => true
    ];
  }
  
  function last_nameField() {
    return [
      'name'    => 'last_name',
      'placeholder' => 'Last Name',
      'label'   => 'Last Name',
      'display' => 'InputText',
      'required'  => true
    ];
  }

    function fullNameField () {
        return [
            'name'        => 'full_name',
            'label'        => 'FullName',
            'required'    => true,
            'display'    => 'InputText'
        ];
    }

    function titleField () {
        return [
            'name'        => 'title',
            'label'        => 'Title',
            'required'    => true,
            'display'    => 'InputText'
        ];
    }

    function emailField () {
        return [
            'name'        => 'email',
            'label'        => 'Email',
            'required'    => true,
            'display'    => 'InputText'
        ];
    }

    function homepageField () {
        return [
            'display' => 'InputText',
            'name' => 'homepage'
        ];
    }


    function phoneField () {
        return [
            'name'        => 'phone',
            'label'        => 'Phone',
            'required'    => true,
            'display'    => 'InputText'
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
            'name'        => 'status',
            'required'    => true,
            'options'    => array(
                'published'    => 'Published',
                'draft'        => 'Draft'
            ),
            'display'    => 'Select',
            'nullable'    => false,
            'default'    => 'published'
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
    
    function imageField () {
    return [
      'name' => 'image',
      'label' => 'List View',
      'display' => 'InputFile'
    ];
  }

  function imageFeaturedField () {
    return [
      'name' => 'image_feature',
      'label' => 'Featured View',
      'display' => 'InputFile'
    ];
  }


    public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

           <div class="bottom-container">
              {{#if profiles}}
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
                            {{#each profiles}}
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
                        {{#FieldLeft first_name "First Name"}}{{/FieldLeft}}
                        {{#FieldLeft last_name "Last Name"}}{{/FieldLeft}}
                            {{#FieldLeft full_name FullName}}{{/FieldLeft}}
                            {{#FieldLeft title Title}}{{/FieldLeft}}
                            {{#FieldLeft email Email}}{{/FieldLeft}}
                            {{#FieldLeft homepage Homepage}}{{/FieldLeft}}
                            {{#FieldLeft phone Phone}}{{/FieldLeft}}
                        {{#FieldLeft description Summary}}{{/FieldLeft}}
                            {{{id}}}
                        {{/DocumentFormLeft}}                 
                        
                        {{#DocumentFormRight}}
                            {{#DocumentButton}}{{/DocumentButton}}
                            {{#FieldFull status}}{{/FieldFull}}
                            <div class="ui clearing divider"></div>
                            {{#FieldLeft featured}}{{/FieldLeft}}
                            <br />
                            {{#FieldLeft pinned}}{{/FieldLeft}}
                        {{/DocumentFormRight}}
                    </div>    
                <div class="ui tab" data-tab="Images">
                    {{#DocumentFormLeft}}
                        {{#FieldLeft image "List View"}}{{/FieldLeft}}
                        {{#FieldLeft image_feature Featured}}{{/FieldLeft}}
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