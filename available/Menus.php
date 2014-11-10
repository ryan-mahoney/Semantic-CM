<?php
/*
 * @version .8
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Menus.php
 * @mode upgrade
 * .6 definiton and description for count added
 * .7 name attributes
 * .8 add CSS field
 */
namespace Manager;

class Menus {
    public $collection = 'Collection\Menus';
    public $title = 'Menus';
    public $titleField = 'label';
    public $singular = 'Menu';
    public $definition = 'Menus determine how a site is organized. Use them as a road map to lay out your content. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $sort = '{"sort_key":1, "created_date":-1}';

    public function labelField () {
        return [
            'name'          => 'label',
            'placeholder'   => 'Title',
            'required'      => true,
            'display'       => 'Field\InputText'
        ];
    }    

    public function urlField () {
        return [
            'name'          => 'url',
            'placeholder'   => 'URL',
            'required'      => false,
            'display'       => 'Field\InputText'
        ];
    }

    public function imageField () {
        return [
            'name'          => 'file',
            'placeholder'   => 'Image',
            'display'       => 'Field\InputFile'
        ];
    }

    public function linkField() {
        return [
            'name'          => 'link',
            'required'      => false,
            'display'       => 'Field\Manager',
            'manager'       => 'MenuLinks'
        ];
    }

    public function cssField() {
        return [
            'name'          => 'css',
            'required'      => false,
            'display'       => 'Field\InputText'
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>
            <div class="bottom-container">
               {{#if menus}}
                    {{{ManagerIndexPagination pagination=pagination}}}
                    {{{ManagerIndexButtons metadata=metadata}}}
                    <table class="ui large table segment manager sortable">
                        <col width="10%">
                        <col width="40%">
                        <col width="20%">
                        <col width="20%">
                        <col width="10%">
                        <thead>
                            <tr>
                                <th><i class="shuffle basic icon"></i></th>
                                <th>Title</th>
                                <th>URL</th>
                                <th>Created Date</th>
                                <th class="trash">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{#each menus}}
                                <tr data-id="{{dbURI}}">
                                    <td class="handle"><i class="reorder icon"></i></td>
                                    <td>{{label}}</td>
                                    <td>{{url}}</td>
                                    <td>{{{MongoDate created_date format="m/d/Y"}}}</td>
                                    <td><div class="manager trash ui icon button"><i class="trash icon"></i></div></td>
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
                        {{{ManagerField . class="left" name="label" label="Title" required="true"}}}
                        {{{ManagerField . class="left" name="url" label="URL" required="true"}}}
                        {{{ManagerField . class="left" name="file" label="Image"}}}
                        {{{ManagerField . class="left" name="css" label="Optional CSS Classes"}}}
                        {{{ManagerFieldEmbedded . name="link" manager="MenuLiks" label="Links"}}}
                        {{{id}}}
                        {{{form-token}}}
                    {{{ManagerFormMainColumnClose}}}                 
                    
                    {{{ManagerFormSideColumn}}}
                        {{{ManagerFormButton modified=modified_date}}}
                    {{{ManagerFormSideColumnClose}}}
                </div>
            </form>
HBS;
        return $partial;
    }
}