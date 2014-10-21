<?php
/*
 * @version 2
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsHighlights.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsHighlights {
    public $collection = 'Collection\Events';
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
    public $embedded = true;

    function imageField () {
        return [
            'name' => 'image',
            'label' => 'Image',
            'display' => 'Field\InputFile'
        ];
    }

    function titleField () {
        return [
            'name'      => 'heading',
            'required'  => true,
            'display'   => 'Field\InputText'
        ];
    }

    function descriptionField () {
        return [
            'name' => 'description',
            'display' => 'Field\Textarea'
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedIndexHeader label="Highlight Images"}}}
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
                {{{ManagerEmbeddedIndexEmpty singular="Highlight Image"}}}
            {{/if}}
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedFormHeader metadata=metadata}}}
                {{{ManagerField . class="fluid" name="image" label="Image"}}}
                {{{ManagerField . class="fluid" name="heading" label="Heading"}}}
                {{{ManagerField . class="fluid" name="description" label="Description"}}}
                {{{id}}}
                {{{form-token}}}
            {{{ManagerEmbeddedFormFooter}}}
HBS;
        return $partial;
    }
}

