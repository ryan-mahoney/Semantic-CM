<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Sponsors.php
 * @mode upgrade
 *
 * .3 set categories from correct query
 * .4 remove sort
 * .5 typo
 * .6 definition and description for count added
 */
namespace Manager;

class Sponsors
{
    public $collection = 'Collection\Sponsors';
    public $title = 'Sponsors';
    public $titleField = 'title';
    public $singular = 'Sponsor';
    public $description = '{{count}} sponsors';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images'];
    public $icon = 'us dollar';
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

    public function descriptionField()
    {
        return [
            'name'        => 'description',
            'label'        => 'Description',
            'required'    =>    false,
            'display' => 'Field\Textarea'
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

    public function imageField()
    {
        return [
            'name' => 'image',
            'label' => 'Logo',
            'display' => 'Field\InputFile',
            'tooltip' => 'An image that will be displayed when the entry is listed.'
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

    public function categoriesField()
    {
        return array(
            'name'        => 'categories',
            'label'        => 'Category',
            'required'    => false,
            'options'    => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('categories')->
                        find(['section' => 'Sponsors'])->
                        sort(['title' => 1]),
                    '_id',
                    'title');
            },
            'display'    => 'Field\InputToTags',
            'controlled' => true,
            'multiple' => true,
        );
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

            <div class="bottom-container">
                    {{#if sponsors}}
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
                                {{#each sponsors}}
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
                            {{{ManagerField . class="left" name="title" label="Title" required="true"}}}
                            {{{ManagerField . class="left" name="description" label="Description"}}}
                            {{{ManagerField . class="left" name="url" label="URL"}}}
                            {{{ManagerField . class="left" name="target" label="Target"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}

                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="fluid" name="status"}}}
                            <div class="ui clearing divider"></div>
                            {{{ManagerField . class="left" name="featured"}}}
                            {{{ManagerField . class="fluid" name="categories" label="Categories"}}}
                            {{{ManagerField . class="fluid" name="tags" label="Tags"}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>

                     <div class="ui tab" data-tab="Images">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . class="left" name="image" label="Logo"}}}
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
