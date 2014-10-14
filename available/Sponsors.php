<?php
/*
 * @version .6
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Sponsors.php
 * @mode upgrade
 *
 * .3 set categories from correct query
 * .4 remove sort
 * .5 typo
 * .6 definition and description for count added
 */
namespace Manager;

class Sponsors {
    public $collection = 'sponsors';
    public $title = 'Sponsors';
    public $titleField = 'title';
    public $singular = 'Sponsor';
    public $description = '{{count}} sponsors';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images'];
    public $icon = 'us dollar';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'sponsors',
        'key' => '_id'
    ];

    function titleField() {
        return [
            'name'        => 'title',
            'label'        => 'Title',
            'required'    =>    true,
            'display' => 'InputText'
        ];
    }

    function descriptionField() {
        return [
            'name'        => 'description',
            'label'        => 'Description',
            'required'    =>    false,
            'display' => 'Textarea'
        ];
    }

    function urlField() {
        return [
            'name'        => 'url',
            'label'        => 'URL',
            'required'    =>    true,
            'display' => 'InputText'
        ];
    }

    public function targetField(){
        return [
            'name'        =>'target',
            'label'        => 'Target',
            'required'    => false,
            'options'    => array(
                    '_blank'        =>'New Window',
                    '_self'        =>'Self',
                    '_parent'    =>'Parent',
                    '_top'        =>'Top'
        ),
                'display'    =>'Select',
                'default'=> 'self'    
    
        ];
    }

    function imageField () {
        return [
                'name' => 'image',
                'label' => 'Logo',
                'display' => 'InputFile',
                'tooltip' => 'An image that will be displayed when the entry is listed.'
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
                        find(['section' => 'Sponsors'])->
                        sort(['title' => 1]),
                    '_id', 
                    'title');
            },
            'display'    => 'InputToTags',
            'controlled' => true,
            'multiple' => true
        );
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                    {{#if sponsors}}
                        {{#CollectionPagination}}{{/CollectionPagination}}
                        {{#CollectionButtons}}{{/CollectionButtons}}
                        
                        <table class="ui large table segment manager sortable">
                                <col width="40%">
                                <col width="30%">
                                <col width="20%">
                                <col width="10%">
                            <thead>
                                <tr>
                                   
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each sponsors}}
                                    <tr data-id="{{dbURI}}">
                                        
                                        <td>{{title}}</td>
                                        <td>{{#Capitalize}}{{status}}{{/Capitalize}}</td>
                                        <td>{{#BooleanReadable}}{{featured}}{{/BooleanReadable}}</td>
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
                            {{#FieldLeft url URL}}{{/FieldLeft}}
                            {{#FieldLeft target Target}}{{/FieldLeft}}
                            {{{id}}}
                        {{/DocumentFormLeft}}                 
                        
                        {{#DocumentFormRight}}
                            {{#DocumentButton}}{{/DocumentButton}}
                            {{#FieldFull status}}{{/FieldFull}}
                            <div class="ui clearing divider"></div>
                            {{#FieldLeft featured}}{{/FieldLeft}}
                            {{#FieldFull categories Categories}}{{/FieldFull}}
                            {{#FieldFull tags Tags}}{{/FieldFull}}
                        {{/DocumentFormRight}}
                    </div>

                     <div class="ui tab" data-tab="Images">
                        {{#DocumentFormLeft}}
                            {{#FieldLeft image Logo}}{{/FieldLeft}}
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