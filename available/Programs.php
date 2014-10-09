<?php
/*
 * @version .6
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Programs.php
 * @mode upgrade
 *
 * .3 pull tags from correct collection
 * .4 remove sort
 * .5 typo
 * .6 definition and description for count added
 */

namespace Manager;

class Programs {
    private $field = false;
    public $collection = 'programs';
    public $title = 'Programs';
    public $titleField = 'title';
    public $singular = 'Program';
    public $description = '{{count}} programs';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images', 'SEO'];
    public $icon = 'grid layout';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'programs',
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

    function locationField () {
        return [
            'name' => 'location',
            'label' => 'Address',
            'required' => false,
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

    function imageFeaturedField () {
        return [
            'name' => 'image_feature',
            'label' => 'Featured View',
            'display' => 'InputFile'
        ];
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
                return $this->db->distinct('programs', 'tags');
            }
        ];
    }

    public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#if programs}}
                        {{#CollectionPagination}}{{/CollectionPagination}}
                        {{#CollectionButtons}}{{/CollectionButtons}}
                        
                        <table class="ui large table segment manager sortable">
                                <col width="40%">
                                <col width="30%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                            <thead>
                                <tr>
                                    
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th>Pinned</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each programs}}
                                    <tr data-id="{{dbURI}}">
                                       
                                        <td>{{title}}</td>
                                        <td>{{#Capitalize}}{{status}}{{/Capitalize}}</td>
                                        <td>{{#BooleanReadable}}{{featured}}{{/BooleanReadable}}</td>
                                        <td>{{#BooleanReadable}}{{pinned}}{{/BooleanReadable}}</td>
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
                            {{#FieldLeft location Address}}{{/FieldLeft}}
                            {{{id}}}
                        {{/DocumentFormLeft}}                 
                        
                        {{#DocumentFormRight}}
                            {{#DocumentButton}}{{/DocumentButton}}
                            {{#FieldFull status}}{{/FieldFull}}
                            <div class="ui clearing divider"></div>
                            {{#FieldLeft featured}}{{/FieldLeft}}
                            <br />
                            {{#FieldLeft pinned}}{{/FieldLeft}}
                            <div class="ui clearing divider"></div>
                            {{#FieldFull tags Tags}}{{/FieldFull}}
                        {{/DocumentFormRight}}
                    </div>

                     <div class="ui tab" data-tab="Images">
                        {{#DocumentFormLeft}}
                            {{#FieldLeft image "List View"}}{{/FieldLeft}}
                            {{#FieldLeft image_feature Featured}}{{/FieldLeft}}
                            {{#FieldLeft }}{{/FieldLeft}}
                            {{#FieldLeft }}{{/FieldLeft}}
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
