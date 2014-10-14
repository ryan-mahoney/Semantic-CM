<?php
/*
 * @version .5
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Links.php
 * @mode upgrade
 *
 * .4 remove sort
 * .5 definiton and description for count added
 */
namespace Manager;

class Links {
    private $field = false;
    public $collection = 'links';
    public $title = 'Links';
    public $titleField = 'title';
    public $singular = 'Link';
    public $description = '{{count}} links';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images'];
    public $icon = 'url';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'links',
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

    function descriptionField() {
        return [
            'name'        => 'description',
            'label'        => 'Description',
            'required'    =>    false,
            'display' => 'Textarea'
        ];
    }

    function imageField () {
        return [
                'name' => 'image',
                'label' => 'Image',
                'display' => 'InputFile',
                'tooltip' => 'An image that will be displayed when the entry is listed.'
        ];
    }    
/*
    
    function categoriesField () {
        return array(
                'name'        => 'categories',
                'label'        => 'Choose a Category',
                'required'    => false,
                'tooltip'    => 'Add one or more categories.',
                'options'    => function () {
                return VCPF\Model::db('categories')->
                find(['section' => 'Links'])->
                sort(array('title' => 1))->
                fetchAllGrouped('_id', 'title');
        },
        'display'    => VCPF\Field::selectToPill()
        );
    }
    
    public function featuredField() {
        return array(
            'name' => 'featured',
            'label' => False,
            'required' => false,
                'options' => array(
                    't' => 'Yes',
                    'f' => 'No'
                ),
                'display' => VCPF\Field::inputRadioButton(),
                'default' => 'f'
        );
    }
*/

 public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#if links}}
                        {{#CollectionPagination}}{{/CollectionPagination}}
                        {{#CollectionButtons}}{{/CollectionButtons}}
                        
                        <table class="ui large table segment manager sortable">
                                <col width="80%">
                                <col width="20%">
                            <thead>
                                <tr>
                                    
                                    <th>Title</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each links}}
                                    <tr data-id="{{dbURI}}">
                                        
                                        <td>{{title}}</td>
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
                            {{#FieldLeft url URL}}{{/FieldLeft}}
                            {{#FieldLeft target Target}}{{/FieldLeft}}
                            {{#FieldLeft description Summary}}{{/FieldLeft}}
                            {{{id}}}
                        {{/DocumentFormLeft}}
                        {{#DocumentFormRight}}
                            {{#DocumentButton}}{{/DocumentButton}}
                        {{/DocumentFormRight}}
                    </div>
                    <div class="ui tab" data-tab="Images">
                        {{#DocumentFormLeft}}
                            {{#FieldLeft image Image}}{{/FieldLeft}}
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

    