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
    public $form = 'menus';
    public $title = 'Menus';
    public $singular = 'Menu';
    public $description = '4 menu items';
    public $definition = 'Menus are used for the navigation of your public website.';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $notice = 'Menu Saved';
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
            'placeholder'        => 'Label',
            'required'    => true,
            'display'    => 'InputText'
        ];
    }    

    function urlField () {
        return [
            'name'        => 'url',
            'placeholder'        => 'URL',
            'required'    => false,
            'display'    => 'InputText'
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

    function dateField() {
        return [
            'name'          => 'display_date',
            'label'         => 'Display Date',
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

    public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#CollectionPagination}}{{/CollectionPagination}}
                {{#CollectionButtons}}{{/CollectionButtons}}
                
                <table class="ui large table segment manager">
                    <col width="20%">
                    <col width="70%">
                    <col width="10%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>URL</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each menus}}
                            <tr data-id="{{dbURI}}">
                                <td>{{label}}</td>
                                <td>{{url}}</td>
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
            </div>
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#DocumentHeader}}{{/DocumentHeader}}
                {{#DocumentTabsButtons}}{{/DocumentTabsButtons}}
            </div>

            <div class="bottom-container">
                {{#DocumentFormLeft}}
                    {{#FieldLeft label Label required}}{{/FieldLeft}}
                    {{#FieldLeft url URL required}}{{/FieldLeft}}
                    {{#FieldLeft file Image}}{{/FieldLeft}}
                    {{#FieldEmbedded link menu_links}}{{/FieldEmbedded}}
                    {{{id}}}
                {{/DocumentFormLeft}}                 
                
                {{#DocumentFormRight}}
                    {{#FieldLeft featured}}{{/FieldLeft}}
                    {{#FieldLeft display_date}}{{/FieldLeft}}
                {{/DocumentFormRight}}
            </div>
HBS;
        return $partial;
    }
}