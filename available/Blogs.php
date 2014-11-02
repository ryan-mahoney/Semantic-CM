<?php
/*
 * @version .9
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Blogs.php
 * @mode upgrade
 *
 * .6 add categories to list
 * .7 typo
 * .8 make description use count variable
 * .9 definition added
 * 1 accurate handlebars
 */
namespace Manager;
use MongoDate;

class Blogs {
    public $collection = 'Collection\Blogs';
    public $title = 'Blogs';
    public $titleField = 'title';
    public $singular = 'Blog';
    public $description = '{{count}}';
    public $definition = 'Regularly updated content can be added through blog posts. Entries are displayed in a list view where the most recent appears first. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images', 'External Article', 'SEO'];
    public $icon = 'text file';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';

    public function titleField () {
        return [
            'name'          => 'title',
            'required'      => true,
            'display'       => 'Field\InputText'
        ];
    }

    public function bodyField () {
        return [
            'display'       => 'Field\Redactor',
            'name'          => 'body'
        ];
    }

    public function descriptionField () {
        return [
            'name'          => 'description',
            'display'       => 'Field\Textarea'
        ];
    }

    public function imageField () {
        return [
            'name'          => 'image',
            'display'       => 'Field\InputFile'
        ];
    }

    public function imageFeaturedField () {
        return [
            'name'          => 'image_feature',
            'display'       => 'Field\InputFile'
        ];
    }

    public function statusField () {
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

    public function featuredField () {
        return [
            'name'          => 'featured',
            'label'         => 'Feature',
            'required'      => false,
            'options'       => [
                't' => 'Yes',
                'f' => 'No'
            ],
            'display'       => 'Field\InputSlider',
            'default'       => 'f'
        ];
    }

    public function authorField () {
        return [
            'name'          => 'author',
            'required'      => false,
            'display'       => 'Field\InputText'
        ];
    }

    public function publication_nameField () {
        return [
            'name'          => 'publication_name',
            'required'      => false,
            'display'       => 'Field\InputText'
        ];
    }
    
    public function linkField () {
        return [
            'name'          => 'link',
            'required'      => false,
            'display'       => 'Field\InputText'
        ];
    }
    
    public function date_publishedField() {
        return [
            'name'          => 'date_published',
            'required'      => false,
            'display'       => 'Field\InputDatePicker',
            'transformIn'   => function ($data) {
                return new MongoDate(strtotime($data));
            },
            'transformOut'  => function ($data) {
                return date('m/d/Y', $data->sec);
            },
            'default'       => function () {
                return date('m/d/Y');
            }
        ];
    }

    public function commentsField () {
        return [
            'name'          => 'comments',
            'label'         => 'Comments',
            'required'      => false,
            'options'       => [
                't' => 'Yes',
                'f' => 'No'
            ],
            'display'       => 'Field\InputSlider',
            'default'       => 'f'
        ];
    }

    public function pinnedField () {
        return [
            'name'          => 'pinned',
            'label'         => 'Pin',
            'required'      => false,
            'options'       => [
                't' => 'Yes',
                'f' => 'No'
            ],
            'display'       => 'Field\InputSlider',
            'default'       => 'f'
        ];
    }

    public function dateField() {
        return [
            'name'          => 'display_date',
            'required'      => true,
            'display'       => 'Field\InputDatePicker',
            'transformIn'   => function ($data) {
                return new MongoDate(strtotime($data));
            },
            'transformOut'  => function ($data) {
                return date('m/d/Y', $data->sec);
            },
            'default'       => function () {
                return date('m/d/Y');
            }
        ];
    }

    public function code_nameField () {
        return [
            'name'          => 'code_name',
            'display'       => 'Field\InputText'
        ];
    }

    public function tagsField () {
        return [
            'name'          => 'tags',
            'required'      => false,
            'transformIn'   => function ($data) {
                if (is_array($data)) {
                    return $data;
                }
                return $this->field->csvToArray($data);
            },
            'display'       => 'Field\InputToTags',
            'multiple'      => true,
            'options'       => function () {
                return $this->db->distinct('blogs', 'tags');
            }
        ];
    }

    public function categoriesField () {
        return [
            'name'          => 'categories',
            'required'      => false,
            'options'       => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('categories')->
                        find(['section' => 'Blog'])->
                        sort(['title' => 1]),
                    '_id', 
                    'title');
            },
            'display'       => 'Field\InputToTags',
            'controlled'    => true,
            'multiple'      => true
        ];
    }

    public function languageField () {
        return [
            'name'          => 'language',
            'required'      => false,
            'options'       => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('languages')->
                        find()->
                        sort(['name' => 1]),
                    'code_name', 
                    'name');
            },
            'display'       => 'Field\InputToTags',
            'controlled'    => true,
            'multiple'      => false
        ];
    }

    public function aclField () {
        return [
            'name'          => 'acl',
            'required'      => false,
            'options'       => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('user_groups')->
                        find()->
                        sort(['name' => 1]),
                    'name', 
                    'name');
            },
            'display'       => 'Field\InputToTags',
            'controlled'    => true,
            'multiple'      => true,
            'default'       => 'public'
        ];
    }

    public function authorsField () {
        return [
            'name'          => 'authors',
            'required'      => false,
            'options'       => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('profiles')->
                        find()->
                        sort(['first_name' => 1]),
                    '_id', 
                    'title');
            },
            'display'       => 'Field\InputToTags',
            'controlled'    => true,
            'multiple'      => true
        ];
    }

    public function metakeywordsField () {
        return [
            'name'          => 'metadata_keywords',
            'display'       => 'Field\InputText'
        ];
    }

    public function metadescriptionField () {
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
                {{#if blogs}}
                    {{{ManagerIndexPagination pagination=pagination}}}
                    {{{ManagerIndexButtons metadata=metadata}}}
                
                    <table class="ui large table segment manager sortable">
                        <col width="45%">
                        <col width="25%">
                        <col width="15%">
                        <col width="15%">
                        <col width="10%">
                        <thead>
                               <tr>
                                  <th>Title</th>
                                  <th>Status</th>
                                  <th>Categories</th>
                                  <th>Featured</th>
                                  <th>Pinned</th>
                                  <th class="trash">Delete</th>
                               </tr>
                        </thead>
                        <tbody>
                            {{#each blogs}}
                                <tr data-id="{{dbURI}}">
                                    <td>{{title}}</td>
                                    <td>{{{Capitalize status}}}</td>
                                    <td>{{{CategoriesCSV categories}}}</td>
                                    <td>{{{BooleanReadable featured}}}</td>
                                    <td>{{{BooleanReadable pinned}}}</td>
                                    <td>
                                       <div class="manager trash ui icon button"><i class="trash icon"></i></div>
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
                            {{{ManagerField . name="title" class="left" label="Title" required="true"}}}
                            {{{ManagerField . name="body" class="left" label="Body"}}}
                            {{{ManagerField . name="description" class="left" label="Summary"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . name="status" class="fluid"}}}
                            <br />
                            {{{ManagerField . name="display_date" class="fluid"}}}
                            <div class="ui clearing divider"></div>
                            {{{ManagerField . name="featured" class="left"}}}
                            <br />
                            {{{ManagerField . name="pinned" class="left"}}}
                            <br />
                            {{{ManagerField . name="comments" class="left"}}}
                            <div class="ui clearing divider"></div>
                            {{{ManagerField . name="categories" class="fluid" label="Categories"}}}
                            {{{ManagerField . name="authors" class="fluid" label="Authors"}}}
                            {{{ManagerField . name="tags" class="fluid" label="Tags"}}}
                            {{{ManagerField . name="language" class="fluid" label="Language"}}}
                            {{{ManagerField . name="acl" class="fluid" label="Access Groups"}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>

                    <div class="ui tab" data-tab="Images">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . name="image" class="left" label="List View"}}}
                            {{{ManagerField . name="image_feature" class="left" label="Featured"}}}
                        {{{ManagerFormMainColumnClose}}}
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>

                    <div class="ui tab" data-tab="External Article">
                         {{{ManagerFormMainColumn}}}
                            {{{ManagerField . name="author" class="left" label="Author"}}}
                            {{{ManagerField . name="publication_name" class="left" label="Publication"}}}
                            {{{ManagerField . name="link" class="left" label="URL"}}}
                            {{{ManagerField . name="date_published" class="left" label="Date Published"}}}
                        {{{ManagerFormMainColumnClose}}}
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                    
                    <div class="ui tab" data-tab="SEO">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . name="code_name" class="left" label="Slug"}}}
                            {{{ManagerField . name="metadata_description" class="left" label="Description"}}}
                            {{{ManagerField . name="metadata_keywords" class="left" label="Keywords"}}}
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