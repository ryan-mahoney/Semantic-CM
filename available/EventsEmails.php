<?php
/*
 * @version 2
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsEmails.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsEmails
{
    public $collection = 'Collection\Events';
    public $title = 'Emails';
    public $titleField = 'title';
    public $singular = 'Email';
    public $description = '{{count}} emails';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $embedded = true;

    public function subjectField()
    {
        return [
            'name'      => 'email_subject',
            'label'     => 'Subject',
            'required'  => true,
            'display'   => 'Field\InputText'
        ];
    }

    public function bodyField()
    {
        return [
            'display' => 'Field\Redactor',
            'name' => 'email_body'
        ];
    }

    public function titleField()
    {
        return [
            'name'      => 'title',
            'label'     => 'Title',
            'required'  => true,
            'display'   => 'Field\InputText'
        ];
    }

    public function sendDateField()
    {
        return [
            'name'          => 'send_date',
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

    public function typeField()
    {
        return [
            'name' => 'type',
            'label' => 'Type',
            'required' => true,
            'display' => 'Field\InputToTags',
            'multiple' => false,
            'options' => function () {
                $existing = $this->db->distinct('events_emails', 'type');
                if (empty($existing)) {
                    $existing = [];
                }
                $common = ['Choose Message Type', 'Welcome', 'Reminder', 'Thankyou', 'Receiept'];
                $emailType = array_unique(array_merge($existing, $common));
                sort($emailType);

                return $emailType;
            }
        ];
    }

    public function ccField()
    {
        return array(
            'name' => 'cc',
            'label' => 'Carbon Copy To',
            'required' => true,
            'display' => 'Field\InputText',
        );
    }

    public function bccField()
    {
        return array(
            'name' => 'bcc',
            'label' => 'Blind Carbon Copy To',
            'required' => true,
            'display' => 'Field\InputText',
        );
    }

    public function tagsField()
    {
        return [
            'name' => 'tags',
            'label' => 'Tags',
            'required' => false,
            'transformIn' => function ($data) {
                if (is_array($data)) {
                    return $data;
                }

                return $this->field->csvToArray($data);
            },
            'display' => 'Field\InputToTags',
            'multiple' => true,
            'options' => function () {
                return $this->db->distinct('events_emails', 'tags');
            }
        ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedIndexHeader label="Emails"}}}
            {{#if email_sub}}
                <table class="ui table manager segment">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each email_sub}}
                            <tr data-id="{{dbURI}}">
                                <td>{{title}}</td>
                                <td>{{email_subject}}</td>
                                <td>{{type}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{{ManagerEmbeddedIndexEmpty singular="Email"}}}
            {{/if}}
HBS;

        return $partial;
    }

    public function formPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedFormHeader metadata=metadata}}}
                {{{ManagerField . class="fluid" name="from_address" label="From Address"}}}
                {{{ManagerField . class="fluid" name="email_subject"}}}
                {{{ManagerField . class="fluid" name="email_body"}}}
                {{{ManagerField . class="fluid" name="title" label="Title}}}
                {{{ManagerField . class="fluid" name="send_date" label="Send Date"}}}
                {{{ManagerField . class="fluid" name="type" label="Type"}}}
                {{{ManagerField . class="fluid" name="cc" label="CarbonCopy To"}}}
                {{{ManagerField . class="fluid" name="bcc" label="Blind Carbon Copy To"}}}
                {{{ManagerField . class="fluid" name="tags" label="Tags"}}}
                {{{id}}}
                {{{form-token}}}
            {{{ManagerEmbeddedFormFooter}}}
            <div style="padding-bottom:100px"></div>
HBS;

        return $partial;
    }
}
