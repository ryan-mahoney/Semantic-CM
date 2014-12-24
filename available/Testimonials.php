<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Testimonials.php
 * @mode upgrade
 *
 * .4 use name not title
 * .5 remove sort
 * .6 typo
 * .7 definition and description for count added
 */
namespace Manager;

class Testimonials
{
    public $collection = 'Collection\Testimonials';
    public $title = 'Testimonials';
    public $titleField = 'name';
    public $singular = 'Testimonial';
    public $description = '{{count}} testimonials';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images'];
    public $icon = 'chat';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';

    public function titleField()
    {
        return [
            'name'        => 'name',
            'required'    => true,
            'display'    => 'Field\InputText'
        ];
    }

    public function locationField()
    {
        return [
            'name' => 'location',
            'display'    => 'Field\InputText'
        ];
    }

    public function occupationField()
    {
        return array(
            'name' => 'occupation',
            'display'    => 'Field\InputText',
        );
    }

    public function messageField()
    {
        return [
            'name' => 'message',
            'display' => 'Field\Redactor',
        ];
    }

    public function messageshortField()
    {
        return [
            'name' => 'messageshort',
            'display' => 'Field\Redactor',
        ];
    }

    public function imageField()
    {
        return [
            'name' => 'image',
            'display' => 'Field\InputFile'
        ];
    }

    public function statusField()
    {
        return [
            'name'        => 'status',
            'required'    => true,
            'options'    => array(
                'published'    => 'Published',
                'draft'        => 'Draft',
            ),
            'display'    => 'Field\Select',
            'nullable'    => false,
            'default'    => 'published'
        ];
    }

    public function dateField()
    {
        return [
            'name'            => 'display_date',
            'required'        => true,
            'display'        => 'Field\InputDatePicker',
            'transformIn'    => function ($data) {
                return new \MongoDate(strtotime($data));
            },
            'transformOut'    => function ($data) {
                return date('m/d/Y', $data->sec);
            },
            'default'        => function () {
                return date('m/d/Y');
            }
        ];
    }

    public function featuredField()
    {
        return [
            'name' => 'featured',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No',
            ),
            'display' => 'Field\InputSlider',
            'default' => 'f'
        ];
    }

    public function approvedField()
    {
        return [
            'name' => 'approved',
            'label' => false,
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No',
            ),
            'display' => 'Field\InputRadioButton',
            'default' => 'f'
        ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

            <div class="bottom-container">
                {{#if testimonials}}
                    {{{ManagerIndexPagination pagination=pagination}}}
                    {{{ManagerIndexButtons metadata=metadata}}}

                    <table class="ui large table segment manager sortable">
                        <col width="40%">
                        <col width="30%">
                        <col width="20%">
                        <col width="10%">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th class="trash">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{#each testimonials}}
                                <tr data-id="{{dbURI}}">
                                    <td>{{title}}</td>
                                    <td>{{{Capitalize status}}}</td>
                                    <td>{{{BooleanReadable featured}}}</td>
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
                            {{{ManagerField . class="left" name="title" label="Name" required="true"}}}
                            {{{ManagerField . class="left" name="location" label="Location" required="true"}}}
                            {{{ManagerField . class="left" name="occupation" label="Occupation"}}}
                            {{{ManagerField . class="left" name="message" label="Message"}}}
                            {{{ManagerField . class="left" name="messageshort" label="Short Message"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}

                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="fluid" name="status"}}}
                            <br />
                            {{{ManagerField . class="fluid" name="display_date"}}}
                            <div class="ui clearing divider"></div>
                            {{{ManagerField . class="left" name="featured"}}}
                            <br />
                            {{{ManagerField . class="left" name="approved"}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>

                     <div class="ui tab" data-tab="Images">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . class="left" name="image" label="Image"}}}
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
