<?php
/*
 * @version .4
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/membership_levels.php
 * @mode upgrade
 * .4 definition and description for count added
 */
namespace Manager;

class membership_levels {
    private $field = false;
    public $collection = 'membership_levels';
    public $title = 'Membership';
    public $titleField = 'title';
    public $singular = 'Membership Level';
    public $description = '{{count}} membership levels';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main'];
    public $icon = 'trophy';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'membership_levels',
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

    function priceField () {
        return [
            'name' => 'price',
            'label' => 'Price',
            'required' => true,
            'display'    => 'InputText'
        ];
    }

    function termField () {
        return [
            'name'         => 'term',
            'label'     => 'Term',
            'required'     => false,
            'display'     => 'Select',
            'options'    => [
                'Annual'    => 'Annual',
                'Perpetual'    => 'Perpetual'
            ],
            'default' => null,
            'nullable'    => 'Choose...'
        ];
    }

    function descriptionField () {
        return array(
            'name' => 'description',
            'label' => 'Description',
            'required' => false,
            'display' => 'Ckeditor'
        );
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

    /*
    function nameField () {
        return array(
            'name' => 'name',
            'label' => 'Name',
            'required' => true,
            'display' => VCPF\Field::inputText()
        );
    }*/

    public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#if membership_levels}}
                    {{#CollectionPagination}}{{/CollectionPagination}}
                    {{#CollectionButtons}}{{/CollectionButtons}}
                    
                    <table class="ui large table segment manager sortable">
                        <col width="10%">
                        <col width="40%">
                        <col width="20%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <thead>
                            <tr>
                                <th><i class="shuffle basic icon"></i></th>
                                <th>Title</th>
                                <th>Term</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th class="trash">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{#each membership_levels}}
                                <tr data-id="{{dbURI}}">
                                    <td class="handle"><i class="reorder icon"></i></td>
                                    <td>{{title}}</td>
                                    <td>{{term}}</td>
                                    <td>{{price}}</td>
                                    <td>{{#Capitalize}}{{status}}{{/Capitalize}}</td>
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
                            {{#FieldLeft price Price}}{{/FieldLeft}}
                            {{#FieldLeft term Term}}{{/FieldLeft}}
                            {{#FieldLeft description Description}}{{/FieldLeft}}
                            {{{id}}}
                        {{/DocumentFormLeft}}                 
                    
                        {{#DocumentFormRight}}
                            {{#DocumentButton}}{{/DocumentButton}}
                            {{#FieldFull status}}{{/FieldFull}}
                            <br />
                        {{/DocumentFormRight}}
                    </div>
                </div>
            </form>
HBS;
        return $partial;
    }
}    