<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/MenuLinks.php
 * @mode upgrade
 *
 * .3 trash in list view
 * .4 small delete button
 * .5 change label
 * .6 definition and description for count added
 * .7 name attributes
 */
namespace Manager;

class MenuLinks {
    public $collection = 'Collection\MenusLinks';
    public $title = 'Menus';
    public $titleField = 'title';
    public $singular = 'Menu';
    public $description = '{{count}} menulinks';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $embedded = true;

    function urlField () {
        return [
            'name'        => 'url',
            'label'        => 'URL',
            'required'    => false,
            'display'    => 'Field\InputText'
        ];
    }

    function titleField () {
        return [
            'name'        => 'title',
            'label'        => 'Title',
            'required'    => false,
            'display'    => 'Field\InputText'
        ];
    }
    
    function targetField () {
        return [
            'name'        => 'target',
            'label'        => 'Redirect',
            'required'    => true,
            'options'    => [
                '_self'        => 'Self',
                '_blank'    => 'Blank',
                '_top'        => 'Top',
                '_parent'    => 'Parent'
            ],
            'display'    => 'Field\Select',
            'nullable'    => false,
            'default'    => 'self'
        ];
    }
    
    function imageField () {
        return [
            'name' => 'file',
            'label' => 'Image',
            'display' => 'Field\InputFile'
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedIndexHeader label="Sub Menus"}}}
            {{#if link}}
                <table class="ui table manager segment sortable">
                      <col width="10%">
                      <col width="40%">
                      <col width="40%">
                      <col width="10%">
                    <thead>
                        <tr>
                            <th><i class="shuffle basic icon"></i></th>
                            <th>Title</th>
                            <th>URL</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each link}}
                            <tr data-id="{{dbURI}}">
                                <td class="handle"><i class="reorder icon"></i></td>
                                <td>{{title}}</td>
                                <td>{{url}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{{ManagerEmbeddedIndexEmpty singular="submenu"}}}
            {{/if}}
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedFormHeader metadata=metadata}}}
                {{{ManagerField . class="fluid" name="title" label="Title"}}}
                {{{ManagerField . class="fluid" name="url" label="URL"}}}
                {{{ManagerField . class="fluid" name="target" label="Target"}}}
                {{{id}}}
                {{{form-token}}}
            {{{ManagerEmbeddedFormFooter}}}
        
HBS;
        return $partial;
    }
}