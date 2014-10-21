<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Pages.php
 * @mode upgrade
 *
 * .4 remove sort
 * .5 definiton and description for count added
 */
namespace Manager;

class Pages {
    public $collection = 'Collection\Pages';
    public $title = 'Pages';
    public $titleField = 'title';
    public $singular = 'Page';
    public $description = '{{count}} pages';
    public $definition = 'Individual content pages can be created and linked to your site through menus. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'SEO'];
    public $icon = 'copy';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    
    function titleField () {
        return [
            'name' => 'title',
            'label' => 'Title',
            'required' => true,
            'display' => 'Field\InputText'            
        ];
    }

    function bodyField () {
        return [
            'name' => 'body',
            'display' => 'Field\Redactor'    
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
            'display'    => 'Field\Select',
            'nullable'    => false,
            'default'    => 'published'
        ];
    }

    function code_nameField () {
        return [
            'name' => 'code_name',
            'display'    => 'Field\InputText'
        ];
    }

    function metakeywordsField () {
        return [
            'name' => 'metadata_keywords',
            'display'    => 'Field\InputText'
        ];
    }

    function metadescriptionField () {
        return [
            'name' => 'metadata_description',
            'display'    => 'Field\InputText'
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
            'display' => 'Field\InputToTags',
            'multiple' => true,
            'options' => function () {
                return $this->db->distinct('pages', 'tags');
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
                        find(['section' => 'Pages'])->
                        sort(['title' => 1]),
                    '_id', 
                    'title');
            },
            'display'    => 'Field\InputToTags',
            'controlled' => true,
            'multiple' => true
        );
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

            <div class="bottom-container">
               {{#if pages}}
                        {{{ManagerIndexPagination pagination=pagination}}}
                        {{{ManagerIndexButtons metadata=metadata}}}
                        
                        <table class="ui large table segment manager sortable">
                            <col width="60%">
                            <col width="20%">
                            <col width="20%">
                            <thead>
                                <tr>
                                    
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each pages}}
                                    <tr data-id="{{dbURI}}">
                                       
                                        <td>{{title}}</td>
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
                            {{{ManagerField . class="left" name="title" label="Title" required="true"}}}
                            {{{ManagerField . class="left" name="body" label="Body"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}                 
                    
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="fluid" name="status"}}}
                            <br />
                            {{{ManagerField . class="fluid" name="categories" label="Categories"}}}
                            {{{ManagerField . class="fluid" name="tags" label="Tags"}}}
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