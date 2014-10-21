<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Blurbs.php
 * @mode upgrade
 *
 * .5 show tags on list
 * .6 sort removed
 * .7 definition and description for count added
 */
namespace Manager;

class Blurbs {
    public $collection = 'Collection\Blurbs';
    public $title = 'Blurbs';
    public $titleField = 'title';
    public $singular = 'Blurb';
    public $description = '{{count}} blurbs';
    public $definition = 'Blurbs are small blocks of content that don\'t fit the conventions of a whole web page. Many appear on multiple pages in the same format. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'basic content';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    
    function titleField () {
        return [
            'name' => 'title',
            'placeholder' => 'Title',
            'required' => true,
            'display' => 'Field\InputText'
        ];
    }

    function bodyField () {
        return [
            'name' => 'body',
            'required' => false,
            'display' => 'Field\Redactor'        
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
                return $this->db->distinct('blurbs', 'tags');
            }
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

            <div class="bottom-container">
                {{#if blurbs}}
                    {{{ManagerIndexPagination pagination=pagination}}}
                    {{{ManagerIndexButtons metadata=metadata}}}
                    <table class="ui large table segment manager sortable">
                        <col width="60%">
                        <col width="20%">
                        <col width="20%">
                        <thead>
                            <tr>
                                
                                <th>Title</th>
                                <th>Tags</th>
                                <th class="trash">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{#each blurbs}}
                                <tr data-id="{{dbURI}}">        
                                    <td>{{title}}</td>
                                    <td>{{{ArrayToCSV tags}}}</td>
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
                            {{{ManagerField . class="left" name="body" required="true"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="fluid" name="tags" label="Tags}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                </div>
            </form>
HBS;
        return $partial;
    }
}