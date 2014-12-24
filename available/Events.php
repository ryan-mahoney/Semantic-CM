<?php
/*
 * @version 2
 * @link https://raw.github.com/virtuecenter/manager/master/available/Events.php
 * @mode upgrade
 *
 * .3 set categories correctly
 * .4 sort removed
 * .5 spelling errors
 * .6 definiton and description for count added
 * .7 more embedded docs
 * .8 missing "Field" suffix on discount code method name
 */
namespace Manager;

class Events
{
    public $collection = 'Collection\Events';
    public $title = 'Events';
    public $titleField = 'title';
    public $singular = 'Event';
    public $description = '{{count}} events';
    public $definition = 'These content blocks appear on event streams and calendars. They are organized by date and time, typically include an image, short description, and occasionally, a registration option. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Views', 'Venue', 'Registration', 'Advanced', 'Images', 'SEO'];
    public $icon = 'empty calendar';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';

    public function titleField()
    {
        return [
            'name' => 'title',
            'label' => 'Title',
            'required' => true,
            'display' => 'Field\InputText'
        ];
    }

    public function bodyField()
    {
        return [
            'name' => 'body',
            'label' => 'Body',
            'required' => false,
            'display' => 'Field\Redactor'
        ];
    }

    public function descriptionField()
    {
        return [
            'name' => 'description',
            'label' => 'Summary',
            'display' => 'Field\Textarea'
        ];
    }

    public function timeField()
    {
        return [
            'name' => 'time',
            'label' => 'Time Description',
            'required' => false,
            'display' => 'Field\InputText'
        ];
    }

    public function costField()
    {
        return [
            'name' => 'cost',
            'label' => 'Cost Description',
            'required' => false,
            'display' => 'Field\InputText'
        ];
    }

    public function imageListField()
    {
        return [
            'name' => 'image',
            'label' => 'List View',
            'display' => 'Field\InputFile'
        ];
    }

    public function imageFeaturedField()
    {
        return [
            'name' => 'image_feature',
            'label' => 'Featured View',
            'display' => 'Field\InputFile'
        ];
    }

    public function venueField()
    {
        return [
            'name' => 'venue',
            'label' => 'Venue',
            'required' => false,
            'display' => 'Field\InputText'
        ];
    }

    public function venue_descriptionField()
    {
        return [
            'name' => 'venue_description',
            'label' => 'Description',
            'required' => false,
            'display' => 'Field\Textarea'
        ];
    }

    public function locationField()
    {
        return [
            'name' => 'location',
            'label' => 'Address',
            'required' => false,
            'display' => 'Field\Textarea'
        ];
    }

    public function contact_infoField()
    {
        return [
            'name' => 'contact_info',
            'label' => 'Contact Information',
            'required' => false,
            'display' => 'Field\Textarea'
        ];
    }

    public function urlField()
    {
        return [
            'name' => 'url',
            'label' => 'URL',
            'required' => false,
            'display' => 'Field\InputText'
        ];
    }

    public function mapField()
    {
        return [
            'name' => 'map_url',
            'label' => 'Map URL',
            'required' => false,
            'display' => 'Field\InputText'
        ];
    }

    public function code_nameField()
    {
        return [
            'name' => 'code_name',
            'display'   => 'Field\InputText'
        ];
    }

    public function metakeywordsField()
    {
        return [
            'name' => 'metadata_keywords',
            'display'   => 'Field\InputText'
        ];
    }

    public function metadescriptionField()
    {
        return [
            'name' => 'metadata_description',
            'display'   => 'Field\InputText'
        ];
    }

    public function categoriesField()
    {
        return array(
            'name'      => 'categories',
            'label'     => 'Category',
            'required'  => false,
            'options'   => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('categories')->
                        find(['section' => 'Events'])->
                        sort(['title' => 1]),
                    '_id',
                    'title');
            },
            'display'   => 'Field\InputToTags',
            'controlled' => true,
            'multiple' => true,
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
                return $this->db->distinct('events', 'tags');
            }
        ];
    }

    public function statusField()
    {
        return [
            'name'      => 'status',
            'required'  => true,
            'options'   => array(
                'published' => 'Published',
                'draft'     => 'Draft',
            ),
            'display'   => 'Field\Select',
            'nullable'  => false,
            'default'   => 'published'
        ];
    }

    public function dateField()
    {
        return [
            'name'          => 'display_date',
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

    public function featuredField()
    {
        return [
            'name' => 'featured',
            'label' => 'Feature',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No',
            ),
            'display' => 'Field\InputSlider',
            'default' => 'f'
        ];
    }

    public function pinnedField()
    {
        return [
            'name' => 'pinned',
            'label' => 'Pin',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No',
            ),
            'display' => 'Field\InputSlider',
            'default' => 'f'
        ];
    }

    public function commentsField()
    {
        return [
            'name' => 'comments',
            'label' => 'Comments',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No',
            ),
            'display' => 'Field\InputSlider',
            'default' => 'f'
        ];
    }

    public function header_imageField()
    {
        return [
            'name' => 'header_image',
            'label' => 'Header Image',
            'display' => 'Field\InputFile'
        ];
    }

    public function footer_imageField()
    {
        return [
            'name' => 'footer_image',
            'label' => 'Footer Image',
            'display' => 'Field\InputFile'
        ];
    }

    public function ticket_imageField()
    {
        return [
            'name' => 'ticket_image',
            'label' => 'Ticket Background Image ',
            'display' => 'Field\InputFile'
        ];
    }

    public function events_images_subField()
    {
        return [
            'name' => 'image_sub',
            'label' => 'Venue Images',
            'required' => false,
            'display'   =>  'Field\Manager',
            'manager'   => 'EventsImages'
        ];
    }

    public function events_registrationsField()
    {
        return [
            'name' => 'registration_options',
            'label' => 'Registration Options',
            'required' => false,
            'display'   =>  'Field\Manager',
            'manager'   => 'EventsRegistrations'
        ];
    }

    public function events_discountsField()
    {
        return [
            'name' => 'discount_code',
            'label' => 'Discount Codes',
            'required' => false,
            'display'   =>  'Field\Manager',
            'manager'   => 'EventsDiscounts'
        ];
    }

    public function events_highlightsField()
    {
        return [
            'name' => 'highlight_images',
            'label' => 'Highlight Images',
            'required' => false,
            'display'   =>  'Field\Manager',
            'manager'   => 'EventsHighlights'
        ];
    }

    public function events_linksField()
    {
        return [
            'name' => 'link_sub',
            'label' => 'Link / Menu',
            'required' => false,
            'display'   =>  'Field\Manager',
            'manager'   => 'EventsLinks'
        ];
    }

    public function events_peoplesField()
    {
        return [
            'name' => 'people_sub',
            'label' => 'People',
            'required' => false,
            'display'   =>  'Field\Manager',
            'manager'   => 'EventsPeoples'
        ];
    }

    public function events_sponsorsField()
    {
        return [
            'name' => 'sponsor_sub',
            'label' => 'Sponsor',
            'required' => false,
            'display'   =>  'Field\Manager',
            'manager'   => 'EventsSponsors'
        ];
    }

    public function events_emailsField()
    {
        return [
            'name' => 'email_sub',
            'label' => 'Email',
            'required' => false,
            'display'   =>  'Field\Manager',
            'manager'   => 'EventsEmails'
        ];
    }

    public function requireAttendeesField()
    {
        return [
            'name' => 'require_attendee_names',
            'label' => 'Attendee Names Required',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No',
            ),
            'display' => 'Field\InputSlider',
            'default' => 'f'
        ];
    }

    public function config_options_appField()
    {
        return [
            'name' => 'config_options_app',
            'label' => 'config_options_app',
            'display' => 'Field\InputText'
        ];
    }

    public function config_attendees_appField()
    {
        return [
            'name' => 'config_attendees_app',
            'label' => ' config_attendees_app',
            'display' => 'Field\InputText'
        ];
    }

    public function config_payment_appField()
    {
        return [
            'name' => 'config_payment_app',
            'label' => ' config_payment_app',
            'display' => 'Field\InputText'
        ];
    }

    public function config_receipt_appField()
    {
        return [
            'name' => 'config_receipt_app',
            'label' => ' config_receipt_app',
            'display' => 'Field\InputText'
        ];
    }

    public function config_options_layoutField()
    {
        return [
            'name' => 'config_options_layout',
            'label' => 'config_options_layout',
            'display' => 'Field\InputText'
        ];
    }

    public function config_attendees_layoutField()
    {
        return [
            'name' => 'config_attendees_layout',
            'label' => ' config_attendees_layout',
            'display' => 'Field\InputText'
        ];
    }

    public function config_payment_layoutField()
    {
        return [
            'name' => 'config_payment_layout',
            'label' => ' config_payment_layout',
            'display' => 'Field\InputText'
        ];
    }

    public function config_receipt_layoutField()
    {
        return [
            'name' => 'config_receipt_layout',
            'label' => ' config_receipt_layout',
            'display' => 'Field\InputText'
        ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

            <div class="bottom-container">
                {{#if events}}
                    {{{ManagerIndexPagination pagination=pagination}}}
                    {{{ManagerIndexButtons metadata=metadata}}}
                    <table class="ui large table segment manager sortable">
                        <col width="40%">
                        <col width="20%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th class="trash">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{#each events}}
                                <tr data-id="{{dbURI}}">

                                    <td>{{title}}</td>
                                    <td>{{{MongoDate display_date}}}</td>
                                    <td>{{time}}</td>
                                    <td>{{cost}}</td>
                                    <td>{{{Capitalize status}}}</td>
                                    <td>
                                        <div class="manager trash ui icon button">
                                             <i class="trash icon"></i>
                                         </div>
                                     </td>
                                </tr>
                            {{/each}}
                        </tbody>
                    </table>
                    {{{ManagerIndexPagination pagination=pagination}}}
                {{else}}
                    {{{ManagerIndexBlankSlate metadata=metadata}}}
                {{/if}}
            </div>
HBS;

        return $partial;
    }

    public function formPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerForm spare=id_spare metadata=metadata}}}
                <div class="top-container">
                    {{{ManagerFormHeader metadata=metadata}}}
                    {{{ManagerFormTabs metadata=metadata}}}
                </div>

                <div class="bottom-container">
                    <div class="ui tab active" data-tab="Main">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . class="left" name="title" label="Title" required="true"}}}
                            {{{ManagerField . class="left" name="body Body}}}
                            {{{ManagerField . class="left" name="description" label="Summary"}}}
                            {{{ManagerField . class="left" name="time" label="Time Description"}}}
                            {{{ManagerField . class="left" name="cost" label="Cost Description"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}

                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="fluid" name="status"}}}
                            <br />
                            {{{ManagerField . class="fluid" name="display_date" label="Date"}}}
                            <div class="ui clearing divider"></div>
                            {{{ManagerField . class="left" name="featured"}}}
                            <br />
                            {{{ManagerField . class="left" name="pinned"}}}
                            <br />
                            {{{ManagerField . class="left" name="comments}}}
                            <div class="ui clearing divider"></div>
                            {{{ManagerField . class="fluid" name="categories" label="Categories"}}}
                            {{{ManagerField . class="fluid" name="tags" label="Tags"}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                    <div class="ui tab" data-tab="Views">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . class="left" name="image" label="List View"}}}
                            {{{ManagerField . class="left" name="image_feature" label="Featured"}}}
                            {{{ManagerFieldEmbedded . name="image_sub" manager="EventsImages"}}}
                        {{{ManagerFormMainColumnClose}}}

                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                    <div class="ui tab" data-tab="Venue">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . class="left" name="venue" label="Venue"}}}
                            {{{ManagerField . class="left" name="venue_description" label="Description"}}}
                            {{{ManagerField . class="left" name="location" label="Address"}}}
                            {{{ManagerField . class="left" name="contact_info" label="Contact Information"}}}
                            {{{ManagerField . class="left" name="url" label="URL"}}}
                            {{{ManagerField . class="left" name="map_url" label="Map URL"}}}
                        {{{ManagerFormMainColumnClose}}}

                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                    <div class="ui tab" data-tab="Registration">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerFieldEmbedded . name="registration_options" manager="EventsRegistrations" label="Registration Options"}}}
                            {{{ManagerFieldEmbedded . name="discount_code" manager="EventsDiscounts" label="Discount Codes"}}}
                            {{{ManagerField . class="left" name="require_attendee_names" label="Require Attendee Names"}}}
                        {{{ManagerFormMainColumnClose}}}

                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                    <div class="ui tab" data-tab="Advanced">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerFieldEmbedded . name="link_sub" manager="EventsLinks" label="Links"}}}
                            {{{ManagerFieldEmbedded . name="people_sub" manager="EventsPeoples" label="People"}}}
                            {{{ManagerFieldEmbedded . name="sponsor_sub" manager="EventsSponsors" label="Sponsors"}}}
                            {{{ManagerFieldEmbedded . name="email_sub" manager="EventsEmails" label="Email Messages"}}}
                            {{{ManagerField . class="left" name="config_options_app" label="config_options_app"}}}
                            {{{ManagerField . class="left" name="config_attendees_app" label="config_attendees_app"}}}
                            {{{ManagerField . class="left" name="config_payment_app" label="config_payment_app"}}}
                            {{{ManagerField . class="left" name="config_receipt_app" label="config_receipt_app"}}}
                            {{{ManagerField . class="left" name="config_options_layout" label="config_options_layout"}}}
                            {{{ManagerField . class="left" name="config_attendees_layout" label="config_attendees_layout"}}}
                            {{{ManagerField . class="left" name="config_payment_layout" label="config_payment_layout"}}}
                            {{{ManagerField . class="left" name="config_receipt_layout" label="config_receipt_layout"}}}
                        {{{ManagerFormMainColumnClose}}}

                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                    <div class="ui tab" data-tab="Images">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . class="left" name="header_image" label="Header Image"}}}
                            {{{ManagerField . class="left" name="footer_image" label="Footer Image"}}}
                            {{{ManagerField . class="left" name="ticket_image" label="Ticket Image"}}}
                            {{{ManagerFieldEmbedded . name="highlight_images" manager="EventsHighlights" label="Highlight Images"}}}
                        {{{ManagerFormMainColumnClose}}}

                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                    <div class="ui tab" data-tab="SEO">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . class="left" name="code_name" label="Slug"}}}
                            {{{ManagerField . class="left" name="metadata_description" label="Description"}}}
                            {{{ManagerField . class="left" name="metadata_keywords" label="Keywords"}}}
                        {{{ManagerFormMainColumnClose}}}

                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                </div>
            </form>
HBS;

        return $partial;
    }
}
