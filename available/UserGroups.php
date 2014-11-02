<?php
/*
 * @version .1
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Languages.php
 * @mode upgrade
 *
 */
namespace Manager;
use MongoDate;

class UserGroups {
    public $collection = 'Collection\UserGroups';
    public $title = 'Groups';
    public $titleField = 'name';
    public $singular = 'Group';
    public $definition = 'Groups available for users.';
    public $acl = ['people', 'admin', 'superadmin'];
    public $icon = 'tags';
    public $category = 'People';
    public $sort = '{"name":1}';
    public $after = 'function';
    public $function = 'ManagerSaved';

    public function nameField () {
        return [
            'name'          => 'name',
            'required'      => true,
            'display'       => 'Field\InputText'
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>
            <div class="bottom-container">
                {{#if user_groups}}
                    {{{ManagerIndexPagination pagination=pagination}}}
                    {{{ManagerIndexButtons metadata=metadata}}}
                    <table class="ui large table segment manager sortable">
                        <col width="90%">
                        <col width="10%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th class="trash">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{#each user_groups}}
                                <tr data-id="{{dbURI}}">
                                    <td>{{name}}</td>
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