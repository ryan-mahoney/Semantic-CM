<?php
/*
 * @version 2
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsSponsors.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsSponsors
{
    public $collection = 'Collection\Events';
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
    public $embedded = true;

    public function titleField()
    {
        return [
            'name'      => 'name',
            'label'     => 'Name',
            'required'  => false,
            'display'   => 'Field\InputText'
        ];
    }

    public function urlField()
    {
        return [
            'name'      => 'url',
            'label'     => 'URL',
            'required'  => false,
            'display'   => 'Field\InputText'
        ];
    }

    public function imageField()
    {
        return [
            'name' => 'file',
            'label' => 'Image',
            'display' => 'Field\InputFile'
        ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedIndexHeader label="Sponsors"}}}
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
                {{{ManagerEmbeddedIndexEmpty singular="Sponsor"}}}
            {{/if}}
HBS;

        return $partial;
    }

    public function formPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedFormHeader metadata=metadata}}}
                {{{ManagerField . class="fluid" name="name" label="Name"}}}
                {{{ManagerField . class="fluid" name="url" label="URL"}}}
                {{{ManagerField . class="fluid" name="file" label="Image"}}}
                {{{id}}}
                {{{form-token}}}
            {{{ManagerEmbeddedFormFooter}}}
HBS;

        return $partial;
    }
}
