<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Departments.php
 * @mode upgrade
 *
 * .3 minor fixes
 * .4 definition and description for count added
 */

namespace Manager;

class Departments {
    public $collection = 'Collection\Departments';
    public $title = 'Departments';
    public $titleField = 'title';
    public $singular = 'Department';
    public $description = '{{cout}} departments';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';

    function titleField () {
        return array(
            'name' => 'title',
            'label' => 'Title',
            'required' => true,
            'display' => 'Field\InputText'
        );
    }

    function descriptionField () {
        return [
            'name' => 'description',
            'label' => 'Summary',
            'display' => 'Field\Textarea'
        ];
    }

    public function department_profilesField() {
        return [
            'name' => 'department_profiles',
            'label' => 'Department Profiles',
            'required' => false,
            'display'    =>    'Field\Manager',
            'manager'    => 'department_profiles'
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

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

            <div class="bottom-container">
                {{#if departments}}
                        {{{ManagerIndexPagination pagination=pagination}}}
                        {{{ManagerIndexButtons metadata=metadata}}}
                        <table class="ui large table segment manager">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Section</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each departments}}
                                    <tr data-id="{{dbURI}}">
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
                            {{{ManagerField . class="left" name="title" label="Title" required="true"}}}
                            {{{ManagerField . class="left" name="description" label="Description"}}}
                            {{{ManagerFieldEmbedded . name="department_profiles" manager="DepartmentProfiles" label="Profiles"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}                 
                    
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="left" name="featured"}}}
                            <br />
                            {{{ManagerField . class="left" name="pinned"}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                </div>
            </form>
HBS;
        return $partial;
    }
}
