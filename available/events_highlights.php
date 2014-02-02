<?php
/*
 * @version .3
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/events_highlights.php
 * @mode upgrade
 *
 */
namespace Manager;

class events_highlights {
    private $field = false;
    public $collection = 'events';
    public $title = 'Highlight Images';
    public $titleField = 'title';
    public $singular = 'Highlight Image';
    public $description = '{{count}} highlights';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $notice = 'Image Saved';
    public $embedded = true;
    public $storage = [
        'collection' => 'events',
        'key' => '_id'
    ];

    public function __construct ($field=false) {
        $this->field = $field;
    }

    function imageField () {
        return [
            'name' => 'image',
            'label' => 'Image',
            'display' => 'InputFile'
        ];
    }

    function titleField () {
        return [
            'name'        => 'heading',
            'label'        => 'Heading',
            'required'    => true,
            'display'    => 'InputText'
        ];
    }

    function descriptionField () {
        return [
            'name' => 'description',
            'label' => 'Description',
            'display' => 'Textarea'
        ];
    }


    public function tablePartial () {
        $partial = <<<'HBS'
            {{#EmbeddedCollectionHeader label="Highlight Images"}}
            {{#if highlight_images}}
                <table class="ui table manager segment">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each highlight_images}}
                            <tr data-id="{{dbURI}}">
                                <td>{{image}}</td>
                                <td>{{heading}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{#EmbeddedCollectionEmpty singular="Highlight Image"}}
            {{/if}}
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            {{#EmbeddedHeader}}
            {{#FieldFull image Image}}{{/FieldFull}}
            {{#FieldFull heading Heading}}{{/FieldFull}}
            {{#FieldFull description Description}}{{/FieldFull}}
            {{{id}}}
            {{#EmbeddedFooter}}
HBS;
        return $partial;
    }
}

