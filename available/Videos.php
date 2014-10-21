<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Videos.php
 * @mode upgrade
 *
 * .4 pull categories from correct query
 * .5 remove sort
 * .6 typo
 * .7 definition and description for count added
 */
namespace Manager;

class Videos {
    public $collection = 'Collection\Videos';
    public $title = 'Videos';
    public $titleField = 'title';
    public $singular = 'Video';
    public $description = '{{count}} videos';
    public $definition = 'Upload Youtube links for video galleries here. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'SEO'];
    public $icon = 'facetime video';
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

    function descriptionField(){
        return [
            'name'=>'description',
            'label'=>'Summary',
            'required'=>false,
            'display' => 'Field\Textarea'
        ];
    }

    function imageField () {
        return [
            'name' => 'image',
            'label' => 'Featured Image',
            'display' => 'Field\InputFile'
        ];
    }

    function videoField () {
        return [
            'name' => 'video',
            'label' => 'URL',
            'required' => true,
            'display' => 'Field\InputText'
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

    function dateField() {
        return [
            'name'            => 'display_date',
            'required'        => true,
            'display'        => 'Field\InputDatePicker',
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

    function featuredField () {
        return [
            'name' => 'featured',
            'label' => 'Feature',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'Field\InputSlider',
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
            'display' => 'Field\InputSlider',
            'default' => 'f'
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

    function categoriesField () {
        return array(
            'name'        => 'categories',
            'label'        => 'Category',
            'required'    => false,
            'options'    => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('categories')->
                        find(['section' => 'Videos'])->
                        sort(['title' => 1]),
                    '_id', 
                    'title');
            },
            'display'    => 'Field\InputToTags',
            'controlled' => true,
            'multiple' => true
        );
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
                return $this->db->distinct('blogs', 'tags');
            }
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

            <div class="bottom-container">
                {{#if videos}}
                        {{{ManagerIndexPagination pagination=pagination}}}
                        {{{ManagerIndexButtons metadata=metadata}}}
                        
                        <table class="ui large table segment manager soratble">
                            <col width="20%">
                            <col width="40%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <thead>
                                <tr>
                                    <th>Video</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Feature</th>
                                    <th>Pinned</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each videos}}
                                    <tr data-id="{{dbURI}}">
                                       
                                         <td>{{video}}</td>
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
                            {{{ManagerField . class="left" name="image" label="Featured" label="Image"}}}
                            {{{ManagerField . class="left" name="video" label="URL"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}                 
                    
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="fluid" name="status"}}}
                            <br />
                            {{{ManagerField . class="fluid" name="display_date"}}}
                             <div class="ui clearing divider"></div>
                            {{{ManagerField . class="left" name="featured"}}}
                            <br />
                            {{{ManagerField . class="left" name="pinned"}}}
                             <div class="ui clearing divider"></div>
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