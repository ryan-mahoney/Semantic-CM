<?php
/*
 * @version 2
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsExceptions.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsExceptions
{
    public $collection = 'Collection\Events';
    public $title = 'Exception Dates';
    public $titleField = 'title';
    public $singular = 'Exception Date';
    public $description = '{{count}} exceptions';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $embedded = true;

    public function dateField()
    {
        return [
            'name'          => 'date',
            'required'      => true,
            'display'       => 'Field\InputDatePicker',
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

    public function noticeField()
    {
        return [
            'name'      => 'notice',
            'label'     => 'Notice',
            'required'  => true,
            'display'   => 'Field\InputText'
        ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedIndexHeader label="Exceptions Dates"}}}
            {{#if exception_date}}
                <table class="ui table manager segment">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each exception_date}}
                            <tr data-id="{{dbURI}}">
                                <td>{{date}}</td>
                                <td>{{notice}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{{ManagerEmbeddedIndexEmpty singular="Exception Date"}}}
            {{/if}}
HBS;

        return $partial;
    }

    public function formPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedFormHeader metadata=metadata}}}
                {{{ManagerField . class="fluid" name="date" label="Date"}}}
                {{{ManagerField . class="fluid" name="notice" label="Notice"}}}
                {{{id}}}
                {{{form-token}}}
            {{{ManagerEmbeddedFormFooter}}}
HBS;

        return $partial;
    }
}
