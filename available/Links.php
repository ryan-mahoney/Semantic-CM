<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Links.php
 * @mode upgrade
 *
 * .4 remove sort
 * .5 definiton and description for count added
 */
namespace Manager;

class Links
{
    public $collection = 'Collection\Links';
    public $title = 'Links';
    public $titleField = 'title';
    public $singular = 'Link';
    public $description = '{{count}} links';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images'];
    public $icon = 'url';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';

    public function titleField()
    {
        return [
            'name'        => 'title',
            'label'        => 'Title',
            'required'    =>    true,
            'display' => 'Field\InputText'
        ];
    }
    public function urlField()
    {
        return [
            'name'        => 'url',
            'label'        => 'URL',
            'required'    =>    true,
            'display' => 'Field\InputText'
        ];
    }

    public function targetField()
    {
        return [
            'name'        => 'target',
            'label'        => 'Target',
            'required'    => false,
            'options'    => array(
                    '_blank'        => 'New Window',
                    '_self'        => 'Self',
                    '_parent'    => 'Parent',
                    '_top'        => 'Top',
        ),
                'display'    => 'Field\Select',
                'default' => 'self'

        ];
    }

    public function descriptionField()
    {
        return [
            'name'        => 'description',
            'label'        => 'Description',
            'required'    =>    false,
            'display' => 'Field\Textarea'
        ];
    }

    public function imageField()
    {
        return [
                'name' => 'image',
                'label' => 'Image',
                'display' => 'Field\InputFile',
                'tooltip' => 'An image that will be displayed when the entry is listed.'
        ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

            <div class="bottom-container">
                {{#if links}}
                        {{{ManagerIndexPagination pagination=pagination}}}
                        {{{ManagerIndexButtons metadata=metadata}}}

                        <table class="ui large table segment manager sortable">
                                <col width="80%">
                                <col width="20%">
                            <thead>
                                <tr>

                                    <th>Title</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each links}}
                                    <tr data-id="{{dbURI}}">

                                        <td>{{title}}</td>
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
                            {{{ManagerField . class="left" name="url" label="URL"}}}
                            {{{ManagerField . class="left" name="target" label="Target"}}}
                            {{{ManagerField . class="left" name="description" label="Summary"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
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
