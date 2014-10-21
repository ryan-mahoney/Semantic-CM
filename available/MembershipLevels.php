<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/MembershipLevels.php
 * @mode upgrade
 * .4 definition and description for count added
 */
namespace Manager;

class MembershipLevels {
    public $collection = 'Collection\MembershipLevels';
    public $title = 'Membership';
    public $titleField = 'title';
    public $singular = 'Membership Level';
    public $description = '{{count}} membership levels';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main'];
    public $icon = 'trophy';
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

    function priceField () {
        return [
            'name' => 'price',
            'label' => 'Price',
            'required' => true,
            'display'    => 'Field\InputText'
        ];
    }

    function termField () {
        return [
            'name'         => 'term',
            'label'     => 'Term',
            'required'     => false,
            'display'     => 'Field\Select',
            'options'    => [
                'Annual'    => 'Annual',
                'Perpetual'    => 'Perpetual'
            ],
            'default' => null,
            'nullable'    => 'Choose...'
        ];
    }

    function descriptionField () {
        return array(
            'name' => 'description',
            'label' => 'Description',
            'required' => false,
            'display' => 'Field\Redactor'
        );
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

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

            <div class="bottom-container">
                {{#if membership_levels}}
                    {{{ManagerIndexPagination pagination=pagination}}}
                    {{{ManagerIndexButtons metadata=metadata}}}
                    
                    <table class="ui large table segment manager sortable">
                        <col width="10%">
                        <col width="40%">
                        <col width="20%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <thead>
                            <tr>
                                <th><i class="shuffle basic icon"></i></th>
                                <th>Title</th>
                                <th>Term</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th class="trash">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{#each membership_levels}}
                                <tr data-id="{{dbURI}}">
                                    <td class="handle"><i class="reorder icon"></i></td>
                                    <td>{{title}}</td>
                                    <td>{{term}}</td>
                                    <td>{{price}}</td>
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
                            {{{ManagerField . class="left" name="price" label="Price"}}}
                            {{{ManagerField . class="left" name="term" label="Term"}}}
                            {{{ManagerField . class="left" name="description" label="Description"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}                 
                    
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="fluid" name="status"}}}
                            <br />
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                </div>
            </form>
HBS;
        return $partial;
    }
}    