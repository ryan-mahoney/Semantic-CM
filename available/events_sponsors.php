<?php
/*
 * @version .3
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/events_sponsors.php
 * @mode upgrade
 *
 */
namespace Manager;

class events_sponsors {
    private $field = false;
    public $collection = 'events';
    public $title = 'Sponsors';
    public $titleField = 'title';
    public $singular = 'Sponsor';
    public $description = '{{count}} sponsors';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $notice = 'Sponsor Saved';
    public $embedded = true;
    public $storage = [
        'collection' => 'events',
        'key' => '_id'
    ];

    public function __construct ($field=false) {
        $this->field = $field;
    }

    function titleField () {
        return [
            'name'        => 'name',
            'label'        => 'Name',
            'required'    => false,
            'display'    => 'InputText'
        ];
    }

    function urlField () {
        return [
            'name'        => 'url',
            'label'        => 'URL',
            'required'    => false,
            'display'    => 'InputText'
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
            {{#EmbeddedCollectionHeader label="Sponsors"}}
            {{#if sponsor_sub}}
                <table class="ui table manager segment">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each sponsor_sub}}
                            <tr data-id="{{dbURI}}">
                                <td>{{image}}</td>
                                <td>{{name}}</td>
                                <td>{{url}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{#EmbeddedCollectionEmpty singular="Sponsor"}}
            {{/if}}
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            {{#EmbeddedHeader}}
            {{#FieldFull name Name}}{{/FieldFull}}
            {{#FieldFull url URL}}{{/FieldFull}}
            {{#FieldFull file Image}}{{/FieldFull}}
            {{{id}}}
            {{#EmbeddedFooter}}
HBS;
        return $partial;
    }
}

