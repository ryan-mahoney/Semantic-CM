<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Carousels.php
 * @mode upgrade
 *
 *
 * .3 duplicate subcarousels field
 * .4 field name issue
 * .6 sort removed
 * .7 trash smaller
 * .8 definiton and description for count added 
 * .9 name attributes
 */
namespace Manager;

class Carousels {
    public $collection = 'Collection\Carousels';
    public $title = 'Carousel';
    public $titleField = 'title';
    public $singular = 'Carousel';
    public $description = '{{count}} carousels';
    public $definition = 'A carousel is a rotating photo module that most typically appears on a homepage.';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'sign in';
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
    
    function descriptionField () {
        return [
            'name' => 'description',
            'label' => 'Description',
            'display' => 'Field\Textarea'
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
                return $this->db->distinct('carousels', 'tags');
            }
        ];
    }    

    public function carousel_individualField() {
        return [
            'name'          => 'carousel_individual',
            'required'      => false,
            'display'       => 'Field\Manager',
            'manager'       => 'Subcarousels'
        ];
    }
    
    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

            <div class="bottom-container">
                {{#if carousels}}
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
                            {{#each carousels}}
                                <tr data-id="{{dbURI}}">
                                    <td>{{title}}</td>
                                    <td>{{{ArrayToCSV tags}}}</td>
                                    <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
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
                    {{{ManagerFormMainColumn}}}
                        {{{ManagerField . class="left" name="title" label="Title" required="true"}}}
                        {{{ManagerField . class="left" name="description" label="Description" required="true"}}}
                        {{{ManagerFieldEmbedded . name="carousel_individual" manager="Subcarousels" label="Frames"}}}
                        {{{id}}}
                        {{{form-token}}}
                    {{{ManagerFormMainColumnClose}}}                 
                    
                    {{{ManagerFormSideColumn}}}
                        {{{ManagerFormButton modified=modified_date}}}
                        {{{ManagerField . class="fluid" name="tags" label="Tags"}}}
                    {{{ManagerFormSideColumnClose}}}
                </div>
            </form>
HBS;
        return $partial;
    }
}    