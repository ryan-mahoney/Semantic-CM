<?php
/*
 * @version .6
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/menu_links.php
 * @mode upgrade
 *
 * .3 trash in list view
 * .4 small delete button
 * .5 change label
 * .6 definition and description for count added
 * .7 name attributes
 */
namespace Manager;

class menu_links {
    private $field = false;
    public $collection = 'menus_links';
    public $title = 'Menus';
    public $titleField = 'title';
    public $singular = 'Menu';
    public $description = '{{count}} menulinks';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $embedded = true;
    public $storage = [
        'collection' => 'menus',
        'key' => '_id'
    ];

    public function __construct ($field=false) {
        $this->field = $field;
    }

    function urlField () {
        return [
            'name'        => 'url',
            'label'        => 'URL',
            'required'    => false,
            'display'    => 'InputText'
        ];
    }

    function titleField () {
        return [
            'name'        => 'title',
            'label'        => 'Title',
            'required'    => false,
            'display'    => 'InputText'
        ];
    }
    
    function targetField () {
        return [
            'name'        => 'target',
            'label'        => 'Redirect',
            'required'    => true,
            'options'    => [
                '_self'        => 'Self',
                '_blank'    => 'Blank',
                '_top'        => 'Top',
                '_parent'    => 'Parent'
            ],
            'display'    => 'Select',
            'nullable'    => false,
            'default'    => 'self'
        ];
    }
    
    function imageField () {
        return [
            'name' => 'file',
            'label' => 'Image',
            'display' => 'InputFile'
        ];
    }

    public function tablePartial () {
        $partial = <<<'HBS'
            {{#EmbeddedCollectionHeader label="Sub Menus"}}
            {{#if link}}
                <table class="ui table manager segment sortable">
                      <col width="10%">
                      <col width="40%">
                      <col width="40%">
                      <col width="10%">
                    <thead>
                        <tr>
                            <th><i class="shuffle basic icon"></i></th>
                            <th>Title</th>
                            <th>URL</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each link}}
                            <tr data-id="{{dbURI}}">
                                <td class="handle"><i class="reorder icon"></i></td>
                                <td>{{title}}</td>
                                <td>{{url}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{#EmbeddedCollectionEmpty singular="submenu"}}
            {{/if}}
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            {{#EmbeddedHeader}}
            {{#FieldFull title Title}}{{/FieldFull}}
            {{#FieldFull url URL}}{{/FieldFull}}
            {{#FieldFull target Target}}{{/FieldFull}}
            {{{id}}}
            {{#EmbeddedFooter}}
        
HBS;
        return $partial;
    }
}