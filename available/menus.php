<?php
/*
 * @version .2
 * @link https://raw.github.com/virtuecenter/manager/master/available/menus.php
 * @mode upgrade
 */
namespace Manager;

class menus {
    private $field = false;
    public $collection = 'menus';
    public $title = 'Menus';
    public $titleField = 'label';
    public $singular = 'Menu';
    public $description = '4 menu items';
    public $definition = 'Menus are used for the navigation of your public website.';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $sort = '{"sort_key":1, "created_date":-1}';
    public $storage = [
        'collection' => 'menus',
        'key' => '_id'
    ];

    public function __construct ($field=false) {
        $this->field = $field;
    }

    function labelField () {
        return [
            'name'        => 'label',
            'placeholder' => 'Title',
            'required'    => true,
            'display'     => 'InputText'
        ];
    }    

    function urlField () {
        return [
            'name'        => 'url',
            'placeholder' => 'URL',
            'required'    => false,
            'display'     => 'InputText'
        ];
    }

    function imageField () {
        return [
            'name' => 'file',
            'placeholder' => 'Image',
            'display' => 'InputFile'
        ];
    }

    public function linkField() {
        return [
            'name' => 'link',
            'required' => false,
            'display'    =>    'Manager',
            'manager'    => 'menu_links'
        ];
    }

    public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
               {{#if menus}}
                        {{#CollectionPagination}}{{/CollectionPagination}}
                        {{#CollectionButtons}}{{/CollectionButtons}}
                        
                        <table class="ui large table segment manager sortable">
                            <col width="10%">
                            <col width="40%">
                            <col width="20%">
                            <col width="20%">
                            <col width="10%">
                            <thead>
                                <tr>
                                    <th><i class="shuffle basic icon"></i></th>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Created Date</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each menus}}
                                    <tr data-id="{{dbURI}}">
                                        <td class="handle"><i class="reorder icon"></i></td>
                                        <td>{{label}}</td>
                                        <td>{{url}}</td>
                                        <td>{{#MongoDate created_date m/d/Y}}{{/MongoDate}}</td>
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
                    {{#DocumentFormLeft}}
                        {{#FieldLeft label Title required}}{{/FieldLeft}}
                        {{#FieldLeft url URL required}}{{/FieldLeft}}
                        {{#FieldLeft file Image}}{{/FieldLeft}}
                        {{#FieldEmbedded link menu_links}}{{/FieldEmbedded}}
                        {{{id}}}
                    {{/DocumentFormLeft}}                 
                    
                    {{#DocumentFormRight}}
                        {{#DocumentButton}}{{/DocumentButton}}
                    {{/DocumentFormRight}}
                </div>
            </form>
HBS;
        return $partial;
    }
}