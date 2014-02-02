<?php
/*
 * @version .8
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/subcategories.php
 * @mode upgrade
 *
 * .3 wrong field referenced
 * .4 set correct lable for embedded doc
 * .5 delete feature
 * .6 fix html
 * .7 definition and description for count added
 * .8 named attributes
 */
namespace Manager;

class subcategories {
    private $field = false;
    public $collection = 'categories';
    public $title = 'Subcategories';
    public $titleField = 'title';
    public $singular = 'Subcategory';
    public $description = '{{count}} subcategories';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $notice = 'Subcategory Saved';
    public $embedded = true;
    public $storage = [
        'collection' => 'categories',
        'key' => '_id'
    ];

    public function __construct ($field=false) {
        $this->field = $field;
    }

    function titleField () {
        return [
            'name'        => 'title',
            'label'        => 'Title',
            'required'    => false,
            'display'    => 'InputText'
        ];
    }

    function imageField () {
        return [
            'name' => 'image',
            'label' => 'Image',
            'display' => 'InputFile'
        ];
    }

    public function tablePartial () {
        $partial = <<<'HBS'
            {{#EmbeddedCollectionHeader label="Subcategories"}}
            {{#if subcategory}}
                <table class="ui table manager segment">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each subcategory}}
                            <tr data-id="{{dbURI}}">
                                <td>{{title}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{#EmbeddedCollectionEmpty singular="Subcategory"}}
            {{/if}}
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            {{#EmbeddedHeader}}
            {{#FieldFull title Title}}{{/FieldFull}}
            {{#FieldFull image Image}}{{/FieldFull}}
            {{{id}}}
            {{#EmbeddedFooter}}
HBS;
        return $partial;
    }
}

