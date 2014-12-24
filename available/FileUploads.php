<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/FileUploads.php
 * @mode upgrade
 *
 * .5 add title
 * .6 add title for form
 * .7 resolve conflict
 * .8 remove sort
 * .9 definition  and description for count added
 */
namespace Manager;

class FileUploads
{
    public $collection = 'Collection\FileUploads';
    public $title = 'File Uploads';
    public $titleField = 'title';
    public $singular = 'File Upload';
    public $description = '{{count}} file uploads';
    public $definition = 'Files that visitors can download from your site, such as high resolution images, PDF\'s, and white papers, can be uploaded here. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main'];
    public $icon = 'cloud upload';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';

    public function titleField()
    {
        return [
            'name'      => 'title',
            'label'     => 'Title',
            'required'  => true,
            'display'   => 'Field\InputText'
        ];
    }

    public function imageField()
    {
        return [
            'name' => 'image',
            'label' => 'File Upload',
            'display' => 'Field\InputFile'
        ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

            <div class="bottom-container">
                {{#if file_uploads}}
                        {{{ManagerIndexPagination pagination=pagination}}}
                        {{{ManagerIndexButtons metadata=metadata}}}

                        <table class="ui large table segment manager sortable">
                            <col width="60%">
                            <col width="20%">
                            <col width="20%">
                            <thead>
                                <tr>

                                    <th>Title</th>
                                    <th>URL</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each file_uploads}}
                                    <tr data-id="{{dbURI}}">

                                         <td>{{title}}</td>
                                         <td>{{image.url}}</td>
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
                            {{{ManagerField . class="left" name="image" label="File Upload" required="true"}}}
                            {{{id}}}
                            {{{form-token}}}
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
