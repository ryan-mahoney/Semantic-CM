<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Publications.php
 * @mode upgrade
 *
 * .6 add categories to list
 * .7 typo
 * .8 definition and description for count added
 * .9 new fields added and updated
 */
namespace Manager;

class Publications {
    public $collection = 'Collection\Publications';
    public $title = 'Publications';
    public $titleField = 'title';
    public $singular = 'Publication';
    public $description = '{{count}} publications';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images', 'SEO'];
    public $icon = 'text file';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    
    function titleField () {
        return [
            'name'        => 'title',
            'label'        => 'Title',
            'required'    => true,
            'display'    => 'Field\InputText'
        ];
    }

    function descriptionField () {
        return [
            'name' => 'description',
            'label' => 'Summary',
            'display' => 'Field\Textarea'
        ];
    }

    function imageField () {
        return [
            'name' => 'image',
            'label' => 'List View',
            'display' => 'Field\InputFile'
        ];
    }

    function fileField () {
        return [
            'name' => 'file',
            'label' => 'File Upload',
            'display' => 'Field\InputFile'
        ];
    }

    function dateField() {
        return [
            'name'          => 'display_date',
            'required'      => true,
            'display'       => 'Field\InputDatePicker',
            'transformIn'   => function ($data) {
                return new \MongoDate(strtotime($data));
            },
            'transformOut'  => function ($data) {
                return date('m/d/Y', $data->sec);
            },
            'default'       => function () {
                return date('m/d/Y');
            }
        ];
    }

    function statusField () {
        return [
            'name'      => 'status',
            'required'  => true,
            'options'   => array(
                'published' => 'Published',
                'draft'     => 'Draft'
            ),
            'display'   => 'Field\Select',
            'nullable'  => false,
            'default'   => 'published'
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
                return $this->db->distinct('publications', 'tags');
            }
        ];
    }

    function categoriesField () {
        return array(
            'name'      => 'categories',
            'label'     => 'Category',
            'required'  => false,
            'options'   => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('categories')->
                        find(['section' => 'Publications'])->
                        sort(['title' => 1]),
                    '_id', 
                    'title');
            },
            'display'   => 'Field\InputToTags',
            'controlled' => true,
            'multiple' => true
        );
    }

    function code_nameField () {
        return [
            'name' => 'code_name',
            'display'   => 'Field\InputText'
        ];
    }

    function metakeywordsField () {
        return [
            'name' => 'metadata_keywords',
            'display'   => 'Field\InputText'
        ];
    }

    function metadescriptionField () {
        return [
            'name' => 'metadata_description',
            'display'   => 'Field\InputText'
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

           <div class="bottom-container">
              {{#if publications}}
                    {{{ManagerIndexPagination pagination=pagination}}}
                    {{{ManagerIndexButtons metadata=metadata}}}
                
                    <table class="ui large table segment manager sortable">
                        <col width="90%">
                        <col width="10%">
                        <thead>
                               <tr>
                                  <th>Title</th>
                                  <th class="trash">Delete</th>
                               </tr>
                        </thead>
                        <tbody>
                            {{#each publications}}
                                <tr data-id="{{dbURI}}">
                                    <td>{{title}}</td>
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
                            {{{ManagerField . class="left" name="title" label="Title" required="true"}}}
                            {{{ManagerField . class="left" name="description" label="Summary"}}}
                            {{{ManagerField . class="left" name="file "File Upload" required="true"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}                 
                        
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="fluid" name="status"}}}
                            <br />
                            {{{ManagerField . class="fluid" name="display_date"}}}
                            <div class="ui clearing divider"></div>
                            {{{ManagerField . class="fluid" name="categories" label="Categories"}}}
                            {{{ManagerField . class="fluid" name="tags" label="Tags"}}}
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