<?php
/*
 * @version 2
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsLinks.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsLinks {
    public $collection = 'Collection\Events';
    public $title = 'Link/Menu';
    public $titleField = 'title';
    public $singular = 'Link/Menu';
    public $description = '{{count}} links';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $embedded = true;

    function urlField () {
        return [
            'name'      => 'url',
            'label'     => 'URL',
            'required'  => false,
            'display'   => 'Field\InputText'
        ];
    }

    function titleField () {
        return [
            'name'      => 'title',
            'label'     => 'Title',
            'required'  => false,
            'display'   => 'Field\InputText'
        ];
    }
    
    function targetField () {
        return [
            'name'      => 'target',
            'label'     => 'Redirect',
            'required'  => true,
            'options'   => [
                '_self'     => 'Self',
                '_blank'    => 'Blank',
                '_top'      => 'Top',
                '_parent'   => 'Parent'
            ],
            'display'   => 'Field\Select',
            'nullable'  => false,
            'default'   => 'self'
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedIndexHeader label="Link / Menu"}}}
            {{#if link_sub}}
                <table class="ui table manager segment">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each link_sub}}
                            <tr data-id="{{dbURI}}">
                                <td>{{url}}</td>
                                <td>{{title}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{{ManagerEmbeddedIndexEmpty singular="Link / Menu"}}}
            {{/if}}
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedFormHeader metadata=metadata}}}
                {{{ManagerField . class="fluid" name="url" label="URL"}}}
                {{{ManagerField . class="fluid" name="title" label="Title"}}}
                {{{ManagerField . class="fluid" name="target" label="Redirect"}}}
                {{{id}}}
                {{{form-token}}}
            {{{ManagerEmbeddedFormFooter}}}
HBS;
        return $partial;
    }
}