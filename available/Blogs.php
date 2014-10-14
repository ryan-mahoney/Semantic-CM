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

class Blogs {
    public $collection = 'Collection\Blogs';
    public $title = 'Blogs';
    public $titleField = 'title';
    public $singular = 'Blog';
    public $description = '{{count}} blogs';
    public $definition = 'Regularly updated content can be added through blog posts. Entries are displayed in a list view where the most recent appears first. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images', 'External Article', 'SEO'];
    public $icon = 'text file';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'blogs',
        'key' => '_id'
    ];
    
    function titleField () {
        return [
            'name'        => 'title',
            'label'        => 'Title',
            'required'    => true,
            'display'    => 'InputText'
        ];
    }

    function bodyField () {
        return [
            'display' => 'Ckeditor',
            'name' => 'body'
        ];
    }

    function descriptionField () {
        return [
            'name' => 'description',
            'label' => 'Summary',
            'display' => 'Textarea'
        ];
    }

    function imageField () {
        return [
            'name' => 'image',
            'label' => 'List View',
            'display' => 'InputFile'
        ];
    }

    function imageFeaturedField () {
        return [
            'name' => 'image_feature',
            'label' => 'Featured View',
            'display' => 'InputFile'
        ];
    }

    function statusField () {
        return [
            'name'        => 'status',
            'required'    => true,
            'options'    => array(
                'published'    => 'Published',
                'draft'        => 'Draft'
            ),
            'display'    => 'Select',
            'nullable'    => false,
            'default'    => 'published'
        ];
    }

    function featuredField () {
        return [
            'name' => 'featured',
            'label' => 'Feature',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

    function authorField () {
        return [
            'name'        => 'author',
            'label'        => 'Author',
            'required'    => false,
            'display'    => 'InputText'
        ];
    }

    function publication_nameField () {
        return [
            'name'        => 'publication_name',
            'label'        => 'Publication',
            'required'    => false,
            'display'    => 'InputText'
        ];
    }
    
    function linkField () {
        return [
            'name'        => 'link',
            'label'        => 'URL',
            'required'    => false,
            'display'    => 'InputText'
        ];
    }
    
    function date_publishedField() {
        return [
            'name'            => 'date_published',
            'label'            => 'Date Published',
            'required'        => false,
            'display'        => 'InputDatePicker',
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

    function commentsField () {
        return [
            'name' => 'comments',
            'label' => 'Comments',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

    function pinnedField () {
        return [
            'name' => 'pinned',
            'label' => 'Pin',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

    function dateField() {
        return [
            'name'            => 'display_date',
            'required'        => true,
            'display'        => 'InputDatePicker',
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

     function code_nameField () {
        return [
            'name' => 'code_name',
            'display'    => 'InputText'
        ];
    }

    function tagsField () {
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
            'display' => 'InputToTags',
            'multiple' => true,
            'options' => function () {
                return $this->db->distinct('blogs', 'tags');
            }
        ];
    }

    function categoriesField () {
        return array(
            'name'        => 'categories',
            'label'        => 'Category',
            'required'    => false,
            'options'    => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('categories')->
                        find(['section' => 'Blog'])->
                        sort(['title' => 1]),
                    '_id', 
                    'title');
            },
            'display'    => 'InputToTags',
            'controlled' => true,
            'multiple' => true
        );
    }

    function authorsField () {
        return array(
            'name'        => 'authors',
            'label'        => 'Authors',
            'required'    => false,
            'options'    => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('profiles')->
                        find()->
                        sort(['first_name' => 1]),
                    '_id', 
                    'title');
            },
            'display'    => 'InputToTags',
            'controlled' => true,
            'multiple' => true
        );
    }

    function metakeywordsField () {
        return [
            'name' => 'metadata_keywords',
            'display'    => 'InputText'
        ];
    }

    function metadescriptionField () {
        return [
            'name' => 'metadata_description',
            'display'    => 'InputText'
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{CollectionHeader metadata=metadata pagination=pagination}}}
            </div>

           <div class="bottom-container">
              {{#if blogs}}
                    {{{CollectionPagination pagination=pagination}}}
                    {{{CollectionButtons metadata=metadata}}}
                
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
                    {{{CollectionPagination pagination=pagination}}}
                {{else}}
                    {{{CollectionEmpty metadata=metadata}}}
              {{/if}}
           </div>
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            {{{Form spare=id_spare metadata=metadata}}}
                <div class="top-container">
                    {{{DocumentHeader metadata=metadata}}}
                    {{{DocumentTabs}}}
                </div>

                <div class="bottom-container">
                    <div class="ui tab active" data-tab="Main">
                        {{{DocumentFormLeft}}}
                            {{{FieldLeft title label="Title" required="true"}}}
                            {{{FieldLeft body label="Body"}}}
                            {{{FieldLeft description label="Summary"}}}
                            {{{id}}}
                        {{{DocumentFormLeftClose}}}
                        
                        {{{DocumentFormRight}}}
                            {{{DocumentButton modified=modified_date}}}
                            {{{FieldFull status}}}
                            <br />
                            {{{FieldFull display_date}}}
                            <div class="ui clearing divider"></div>
                            {{{FieldLeft featured}}}
                            <br />
                            {{{FieldLeft pinned}}}
                            <br />
                            {{{FieldLeft comments}}}
                            <div class="ui clearing divider"></div>
                            {{{FieldFull categories label="Categories"}}}
                            {{{FieldFull authors label="Authors"}}}
                            {{{FieldFull tags label="Tags"}}}
                        {{{DocumentFormRightClose}}}
                    </div>

                    <div class="ui tab" data-tab="Images">
                        {{{DocumentFormLeft}}}
                            {{{FieldLeft image label="List View"}}}
                            {{{FieldLeft image_feature label="Featured"}}}
                        {{{DocumentFormLeftClose}}}                
                        
                        {{{DocumentFormRight}}}
                            {{{DocumentButton modified=modified_date}}}
                        {{{DocumentFormRightClose}}}
                    </div>

                    <div class="ui tab" data-tab="External Article">
                         {{{DocumentFormLeft}}}
                            {{{FieldLeft author label="Author"}}}
                            {{{FieldLeft publication_name label="Publication"}}}
                              {{{FieldLeft link label="URL"}}}
                              {{{FieldLeft date_published label="Date Published"}}}
                        {{{DocumentFormLeftClose}}}
                        
                        {{{DocumentFormRight}}}
                            {{{DocumentButton modified=modified_date}}}
                        {{{DocumentFormRightClose}}}
                    </div>
                    
                    <div class="ui tab" data-tab="SEO">
                        {{{DocumentFormLeft}}}
                            {{{FieldLeft code_name Slug}}}
                            {{{FieldLeft metadata_description Description}}}
                              {{{FieldLeft metadata_keywords Keywords}}}
                        {{{DocumentFormLeftClose}}}
                        
                        {{{DocumentFormRight}}}
                            {{{DocumentButton modified=modified_date}}}
                        {{{DocumentFormRightClose}}}
                    </div>            
                </div>
            </form>
HBS;
        return $partial;
    }
}    