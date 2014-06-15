<?php
/*
 * @version .8
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Publications.php
 * @mode upgrade
 *
 * .6 add categories to list
 * .7 typo
 * .8 definition and description for count added
 * .9 new fields added and updated
 */
namespace Manager;

class Publications {
    private $field = false;
    public $collection = 'publications';
    public $title = 'Publications';
    public $titleField = 'title';
    public $singular = 'Publication';
    public $description = '{{count}} publications';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images', 'SEO'];
    public $icon = 'text file';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'publications',
        'key' => '_id'
    ];
    
    function titleField () {
        return [
            'name'        => 'title',
            'label'        => 'Title',
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

    function imageField () {
        return [
            'name' => 'image',
            'label' => 'List View',
            'display' => 'InputFile'
        ];
    }

    function fileField () {
        return [
            'name' => 'file',
            'label' => 'File Upload',
            'display' => 'InputFile'
        ];
    }

    
    function dateField() {
        return [
            'name'          => 'display_date',
            'required'      => true,
            'display'       => 'InputDatePicker',
            'transformIn'   => function ($data) {
                return new \MongoDate(strtotime($data));
            },
            'transformOut'  => function ($data) {
                return date('m/d/Y', $data->sec);
            },
            'default'       => function () {
                return date('m/d/Y');
            }
        ];
    }

    function statusField () {
        return [
            'name'      => 'status',
            'required'  => true,
            'options'   => array(
                'published' => 'Published',
                'draft'     => 'Draft'
            ),
            'display'   => 'Select',
            'nullable'  => false,
            'default'   => 'published'
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
                return $this->db->distinct('publications', 'tags');
            }
        ];
    }

    function categoriesField () {
        return array(
            'name'      => 'categories',
            'label'     => 'Category',
            'required'  => false,
            'options'   => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('categories')->
                        find(['section' => 'Publications'])->
                        sort(['title' => 1]),
                    '_id', 
                    'title');
            },
            'display'   => 'InputToTags',
            'controlled' => true,
            'multiple' => true
        );
    }

    function code_nameField () {
        return [
            'name' => 'code_name',
            'display'   => 'InputText'
        ];
    }

    function metakeywordsField () {
        return [
            'name' => 'metadata_keywords',
            'display'   => 'InputText'
        ];
    }

    function metadescriptionField () {
        return [
            'name' => 'metadata_description',
            'display'   => 'InputText'
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
                            {{#FieldLeft description Summary}}{{/FieldLeft}}
                            {{#FieldLeft file "File Upload" required}}{{/FieldLeft}}
                            {{{id}}}
                        {{/DocumentFormLeft}}                 
                        
                        {{#DocumentFormRight}}
                            {{#DocumentButton}}{{/DocumentButton}}
                            {{#FieldFull status}}{{/FieldFull}}
                            <br />
                            {{#FieldFull display_date}}{{/FieldFull}}
                            <div class="ui clearing divider"></div>
                            {{#FieldFull categories Categories}}{{/FieldFull}}
                            {{#FieldFull tags Tags}}{{/FieldFull}}
                        {{/DocumentFormRight}}
                    </div>
                     <div class="ui tab" data-tab="Images">
                        {{#DocumentFormLeft}}
                            {{#FieldLeft image "List View"}}{{/FieldLeft}}
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