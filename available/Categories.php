<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Categories.php
 * @mode upgrade
 *
 * .3 duplicate field
 * .6 better handling of section
 * .7 sort removed
 * .8 definition and description for count added
 * .9 name attributes
 * 1.0 better handlebars integration
 */

namespace Manager;

class Categories {
    public $collection = 'Collection\Categories';
    public $title = 'Categories';
    public $titleField = 'title';
    public $singular = 'Category';
    public $description = '{{count}} categories';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'SEO'];
    public $icon = 'checkmark sign';
    public $sort = '{"sort_key":1, "title":1}';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';

    function titleField () {
        return [
            'name'          => 'title',
            'required'      => true,
            'display'       => 'Field\InputText'
        ];
    }

    function sectionField () {
        return [
            'name'          => 'section',
            'required'      => true,
            'display'       => 'Field\InputToTags',
            'multiple'      => false,
            'options'       => function () {
                $existing = $this->db->distinct('categories', 'section');
                if (empty($existing)) {
                    $existing = [];
                }
                $common = ['Blog', 'Books', 'Events', 'Links', 'Pages', 'Galleries', 'Podcasts', 'Sponsors', 'Videos'];
                $categories = array_unique(array_merge($existing, $common));
                sort($categories);
                return $categories;
            }
        ];
    }

    function imageField () {
        return [
            'name'          => 'image',
            'display'       => 'Field\InputFile'
        ];
    }

    public function subcategoryField() {
        return [
            'name'          => 'subcategory',
            'required'      => false,
            'display'       => 'Field\Manager',
            'manager'       => 'Subcategories'
        ];
    }

    function statusField () {
        return [
            'name'          => 'status',
            'required'      => true,
            'options'       => [
                'published'    => 'Published',
                'draft'        => 'Draft'
            ],
            'display'       => 'Field\Select',
            'nullable'      => false,
            'default'       => 'published'
        ];
    }

    function featuredField () {
        return [
            'name'          => 'featured',
            'required'      => false,
            'options'       => [
                't' => 'Yes',
                'f' => 'No'
            ],
            'display'       => 'Field\InputSlider',
            'default'       => 'f'
        ];
    }

    function code_nameField () {
        return [
            'name'          => 'code_name',
            'display'       => 'Field\InputText'
        ];
    }

    function metakeywordsField () {
        return [
            'name'          => 'metadata_keywords',
            'display'       => 'Field\InputText'
        ];
    }

    function metadescriptionField () {
        return [
            'name'          => 'metadata_description',
            'display'       => 'Field\InputText'
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>
            <div class="bottom-container">
                {{#if categories}}
                    {{{ManagerIndexPagination pagination=pagination}}}
                    {{{ManagerIndexButtons metadata=metadata}}}
                    <table class="ui large table segment manager sortable">
                        <col width="10%">
                        <col width="60%">
                        <col width="20%">
                        <col width="10%">
                        <thead>
                            <tr>
                                <th><i class="shuffle basic icon"></i></th>
                                <th>Title</th>
                                <th>Section</th>
                                <th class="trash">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{#each categories}}
                                <tr data-id="{{dbURI}}">
                                    <td class="handle"><i class="reorder icon"></i></td>
                                    <td>{{title}}</td>
                                    <td>{{section}}</td>
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

    public function formPartial () {
        $partial = <<<'HBS'
                {{{ManagerForm spare=id_spare metadata=metadata}}}
                <div class="top-container">
                    {{{ManagerFormHeader metadata=metadata}}}
                    {{{ManagerFormTabs metadata=metadata}}}
                </div>
                <div class="bottom-container">
                    <div class="ui tab active" data-tab="Main">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . name="title" label="Title" required="true" class="left"}}}
                            {{{ManagerField . name="section" label="Section" required="true" class="left"}}}
                            {{{ManagerField . name="image" label="Image" class="left"}}}
                            {{{ManagerFieldEmbedded . name="subcategory" manager="Subcategories"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . name="status" class="fluid"}}}
                            <br />
                            {{{ManagerField . name="featured" class="left"}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                    <div class="ui tab" data-tab="SEO">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . name="code_name" label="Slug" class="left"}}}
                            {{{ManagerField . name="metadata_description" label="Description" class="left"}}}
                            {{{ManagerField . name="metadata_keywords" label="Keywords" class="left"}}}
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