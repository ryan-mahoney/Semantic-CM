<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Podcasts.php
 * @mode upgrade
 *
 * .4 make audio file non-mandatory
 * .5 set categories correctly
 * .6 remove sort
 * .7 typo
 * .8 definition and description for count added
 */
namespace Manager;

class Podcasts
{
    public $collection = 'Collection\Podcasts';
    public $title = 'Podcasts';
    public $titleField = 'title';
    public $singular = 'Podcast';
    public $description = '{{count}} podcasts';
    public $definition = 'Upload MP3 files with descriptions and photos here. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images', 'SEO'];
    public $icon = 'headphones';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';

    public function titleField()
    {
        return [
            'name' => 'title',
            'label' => 'Title',
            'required' => true,
            'display'    => 'Field\InputText'
        ];
    }

    public function descriptionField()
    {
        return [
            'name' => 'description',
            'label' => 'Summary',
            'required' => false,
            'display' => 'Field\Textarea'
        ];
    }

    public function imageField()
    {
        return [
            'name' => 'image',
            'label' => 'List View',
            'display' => 'Field\InputFile'
        ];
    }

    public function audioField()
    {
        return [
            'name' => 'audio',
            'label' => 'File',
            'required' => false,
            'display' => 'Field\InputFile'
        ];
    }

    public function code_nameField()
    {
        return [
            'name' => 'code_name',
            'display'    => 'Field\InputText'
        ];
    }

    public function metakeywordsField()
    {
        return [
            'name' => 'metadata_keywords',
            'display'    => 'Field\InputText'
        ];
    }

    public function metadescriptionField()
    {
        return [
            'name' => 'metadata_description',
            'display'    => 'Field\InputText'
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

    public function categoriesField()
    {
        return array(
            'name'        => 'categories',
            'label'        => 'Category',
            'required'    => false,
            'options'    => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('categories')->
                        find(['section' => 'Podcasts'])->
                        sort(['title' => 1]),
                    '_id',
                    'title');
            },
            'display'    => 'Field\InputToTags',
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
                return $this->db->distinct('blogs', 'tags');
            }
        ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

            <div class="bottom-container">
                {{#if podcasts}}
                        {{{ManagerIndexPagination pagination=pagination}}}
                        {{{ManagerIndexButtons metadata=metadata}}}

                        <table class="ui large table segment manager sortable">
                                <col width="40%">
                                <col width="30%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th>Pinned</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each podcasts}}
                                    <tr data-id="{{dbURI}}">
                                        <td>{{title}}</td>
                                        <td>{{{Capitalize status}}}</td>
                                        <td>{{{BooleanReadable featured}}}</td>
                                        <td>{{{BooleanReadable pinned}}}</td>
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
                            {{{ManagerField . class="left" name="description" label="Summary"}}}
                            {{{ManagerField . class="left" name="audio" label="File"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}

                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="fluid" name="status"}}}
                            <br>
                               {{{ManagerField . class="left" name="featured"}}}
                               {{{ManagerField . class="left" name="pinned"}}}
                               <div class="ui clearing divider"></div>
                               {{{ManagerField . class="fluid" name="categories" label="Categories}}}
                               {{{ManagerField . class="fluid" name="tags" label="Tags}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                    <div class="ui tab" data-tab="Images">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . class="left" name="image" label="List View"}}}
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
