<?php
/*
 * @version .1
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/resources.php
 * @mode upgrade
 *
 */
namespace Manager;

class resources {
    private $field = false;
    public $collection = 'resources';
    public $title = 'Resources';
    public $titleField = 'title';
    public $singular = 'Resource';
    public $description = '{{count}} resources';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images', 'SEO'];
    public $icon = 'text file';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'resources',
        'key' => '_id'
    ];

    public function __construct ($field=false) {
        $this->field = $field;
    }

    function titleField () {
        return [
            'name'        => 'title',
            'label'        => 'Title',
            'required'    => true,
            'display'    => 'InputText'
        ];
    }

    function bodyField () {
        return [
            'display' => 'Ckeditor',
            'name' => 'body'
        ];
    }

    function uploadField () {
        return [
            'name' => 'image',
            'label' => 'File',
            'display' => 'InputFile'
        ];
    }

    function formatField () {
        return [
            'name'        => 'format',
            'label'        => 'Format',
            'required'    => false,
            'tooltip'    => 'Add one or more categories.',
            'options'    =>    [
                'podcast' => 'Audio',
                'file' => 'File',
                'image' => 'Image',
                'link' => 'Link',
                'video' => 'Video'
            ],
        'display'    => 'Select'
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

    function dateField() {
        return [
            'name'            => 'display_date',
            'required'        => true,
            'display'        => 'InputDatePicker',
            'transformIn'    => function ($data) {
                return new \MongoDate(strtotime($data));
            },
            'transformOut'    => function ($data) {
                return date('m/d/Y', $data->sec);
            },
            'default'        => function () {
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

    function tagsField () {
        return [
            'name' => 'tags',
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
                return $this->db->distinct('blogs', 'tags');
            }
        ];
    }

    function categoriesField () {
        return array(
            'name'        => 'categories',
            'label'        => 'Category',
            'required'    => false,
            'options'    => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('categories')->
                        find(['section' => 'Blog'])->
                        sort(['title' => 1]),
                    '_id', 
                    'title');
            },
            'display'    => 'InputToTags',
            'controlled' => true,
            'multiple' => true
        );
    }


    function code_nameField () {
        return [
            'name' => 'code_name',
            'display'    => 'InputText'
        ];
    }

    function metakeywordsField () {
        return [
            'name' => 'metadata_keywords',
            'display'    => 'InputText'
        ];
    }

    function metadescriptionField () {
        return [
            'name' => 'metadata_description',
            'display'    => 'InputText'
        ];
    }
    /*
    function beforeFieldsetTemplate () {
        return function ($admin, &$fieldsets) {
            $fieldsets = str_replace('${particle-container}', file_get_contents('/home/webuser/webroots/vc/ms/html/site/form/ParticleResourceAdmin.html'), $fieldsets);
        };
    }
    
    function afterFieldsetUpdate () {
        return function ($admin) {
            $DOM = VCPF\DOMView::getDOM();
            $DOM['.particle-type-switcher-container']->remove();
            $DOM['.admin-form']->prepend('<input type="hidden" name="vc_ms_site_admin_resourcesadmin[type]" value="' . $admin->activeRecord['type'] . '" />');
            $DOM['.' . $admin->activeRecord['type']]->attr('style', 'display: block');
        };
    }
    
    function speakersField () {
        return array(
                'name'            => 'speakers',
                'label'            => 'Add an Speaker',
                'required'        => false,
                'tooltip'        => 'Add one or more speaker.',
                'options'        => function () {
                    return VCPF\Model::db('users')->
                    find(['classification_tags' => 'speaker'])->
                    sort(array('first_name' => 1))->
                    fetchAllGrouped('_id', ['first_name', 'last_name']);
        },
            'display'        => VCPF\Field::selectToPill()
        );
    }
    
    function parent_collectionField () {
        return [
            'name' => 'parent_collection',
            'label' => '',
            'required' => false,
            'display' => VCPF\Field::inputHidden()
        ];
    }
    
    function parent_idField () {
        return [
            'name' => 'parent_id',
            'label' => '',
            'required' => false,
            'display' => VCPF\Field::inputHidden(),
        ];
    }*/

    public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

           <div class="bottom-container">
              {{#if resources}}
                    {{#CollectionPagination}}{{/CollectionPagination}}
                    {{#CollectionButtons}}{{/CollectionButtons}}
                
                    <table class="ui large table segment manager sortable">
                        <col width="45%">
                        <col width="25%">
                        <col width="15%">
                        <col width="15%">
                        <col width="10%">
                        <thead>
                               <tr>
                                  <th>Title</th>
                                  <th>Status</th>
                                  <th>Categories</th>
                                  <th>Featured</th>
                                  <th>Pinned</th>
                                  <th class="trash">Delete</th>
                               </tr>
                        </thead>
                        <tbody>
                            {{#each resources}}
                                <tr data-id="{{dbURI}}">
                                    <td>{{title}}</td>
                                    <td>{{#Capitalize}}{{status}}{{/Capitalize}}</td>
                                    <td>{{#CategoriesCSV}}{{categories}}{{/CategoriesCSV}}</td>
                                    <td>{{#BooleanReadable}}{{featured}}{{/BooleanReadable}}</td>
                                    <td>{{#BooleanReadable}}{{pinned}}{{/BooleanReadable}}</td>
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
                            {{#FieldLeft body Body}}{{/FieldLeft}}
                            {{#FieldLeft image File}}{{/FieldLeft}}
                            {{#FieldLeft format Format}}{{/FieldLeft}}
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
                            {{#FieldLeft pinned}}{{/FieldLeft}}
                            <br />
                            <div class="ui clearing divider"></div>
                            {{#FieldFull categories Categories}}{{/FieldFull}}
                            {{#FieldFull tags Tags}}{{/FieldFull}}
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