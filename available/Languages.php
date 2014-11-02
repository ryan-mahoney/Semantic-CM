<?php
/*
 * @version .1
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Languages.php
 * @mode upgrade
 *
 */
namespace Manager;
use MongoDate;

class Languages {
    public $collection = 'Collection\Languages';
    public $title = 'Languages';
    public $titleField = 'name';
    public $singular = 'Language';
    public $definition = 'Languages available for website content.';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'globe';
    public $category = 'Content';
    public $sort = '{"name":1}';
    public $after = 'function';
    public $function = 'ManagerSaved';

    function nameField () {
        return [
            'name'          => 'name',
            'required'      => true,
            'display'       => 'Field\InputText'
        ];
    }

    function authorField () {
        return [
            'name'          => 'charset',
            'required'      => true,
            'display'       => 'Field\InputText'
        ];
    }

    function code_nameField () {
        return [
            'name'          => 'code_name',
            'display'       => 'Field\InputText'
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>
            <div class="bottom-container">
                {{#if languages}}
                    {{{ManagerIndexPagination pagination=pagination}}}
                    {{{ManagerIndexButtons metadata=metadata}}}
                
                    <table class="ui large table segment manager sortable">
                        <col width="60%">
                        <col width="15%">
                        <col width="15%">
                        <col width="10%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Charset</th>
                                <th>Code</th>
                                <th class="trash">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{#each languages}}
                                <tr data-id="{{dbURI}}">
                                    <td>{{name}}</td>
                                    <td>{{charset}}</td>
                                    <td>{{code_name}}</td>
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
                            {{{ManagerField . name="name" class="left" label="Name" required="true"}}}
                            {{{ManagerField . name="charset" class="left" label="Charset" required="true"}}}
                            {{{ManagerField . name="code_name" class="left" label="Slug"}}}
                            {{{id}}}
                            {{{form-token}}}
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