<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Books.php
 * @mode upgrade
 *
 * .4 do not require price and url
 * .5 title field not set
 * .6 typo
 * .7 definition and description for count added
 */
namespace Manager;

class Books
{
    public $collection = 'Collection\Books';
    public $title = 'Books';
    public $singular = 'Book';
    public $titleField = 'title';
    public $description = '{{count}} books';
    public $definition = 'This is a customized content page module for the purposes of selling or displaying books. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images', 'SEO'];
    public $icon = 'book';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';

    public function titleField()
    {
        return [
            'name'        => 'title',
            'label'        => 'Title',
            'required'    => true,
            'display'    => 'Field\InputText'
        ];
    }

    public function descriptionField()
    {
        return [
            'name' => 'description',
            'label' => 'Body',
            'display' => 'Field\Redactor'
        ];
    }

    public function short_descriptionField()
    {
        return [
            'name' => 'short_description',
            'label' => 'Summary',
            'display' => 'Field\Textarea'
        ];
    }

    public function imageField()
    {
        return [
            'name' => 'image',
            'label' => 'Book Cover Image',
            'display' => 'Field\InputFile'
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

    public function linkField()
    {
        return [
            'name'        => 'link',
            'label'        => 'URL',
            'required'    => false,
            'display'    => 'Field\InputText'
        ];
    }

    public function priceField()
    {
        return [
            'name'        => 'price',
            'label'        => 'Price',
            'required'    => false,
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
            'options' => [
                't' => 'Yes',
                'f' => 'No',
            ],
            'display' => 'Field\InputSlider',
            'default' => 'f'
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
                return $this->db->distinct('books', 'tags');
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
                        find(['section' => 'Books'])->
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
                {{#if books}}
                        {{{ManagerIndexPagination pagination=pagination}}}
                        {{{ManagerIndexButtons metadata=metadata}}}

                        <table class="ui large table segment manager sortable">
                            <col width="10%">
                            <col width="40%">
                            <col width="20%">
                            <col width="20%">
                            <col width="10%">
                            <thead>
                                <tr>
                                    <th><i class="shuffle basic icon"></i></th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Feature</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each books}}
                                    <tr data-id="{{dbURI}}">
                                        <td class="handle"><i class="reorder icon"></i></td>
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
                            {{{ManagerField . class="left" name="description" label="Body"}}}
                            {{{ManagerField . class="left" name="short_description" label="Summary"}}}
                            {{{ManagerField . class="left" name="link" label="URL"}}}
                            {{{ManagerField . class="left" name="price" label="Price"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}

                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="fluid" name="status"}}}
                            <br>
                            {{{ManagerField . class="left" name="featured"}}}
                            <div class="ui clearing divider"></div>
                            {{{ManagerField . class="fluid" name="categories" label="Categories"}}}
                            {{{ManagerField . class="fluid" name="tags" label="Tags"}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>

                    <div class="ui tab" data-tab="Images">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . class="left" name="image" label="Book Cover"}}}
                            {{{ManagerField . class="left" name="image_list" label="List View"}}}
                            {{{ManagerField . class="left" name="image_feature" label="Featured"}}}
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
