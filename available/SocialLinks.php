<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/SocialLinks.php
 * @mode upgrade
 *
 * .4 lacks title field
 * .5 list view does not make sense
 * .6 fix conflict
 * .7 remove sort
 * .8 definition and description for count added
 */
namespace Manager;

class SocialLinks {
    public $collection = 'Collection\SocialLinks';
    public $title = 'Social Links';
    public $titleField = 'url';
    public $singular = 'Social Link';
    public $description = '{{count}} social links';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main'];
    public $icon = 'url';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    
    function typeField () {
        return [
        'name' => 'type',
        'label' => 'Type',
        'required' => false,
        'options' => [
            'facebook'        =>"Facebook",
            'twitter'        =>"Twitter",
            'googleplus'    =>"Google +",
            'linkedin'        =>"LinkedIn",
            'flickr'        =>"Flickr",
            'youtube'        =>"YouTube",
        ],
                'display' => 'Field\Select',
                'nullable' => true
        ];
    }    
    
    function urlField () {
        return array(
            'name' => 'url',
            'label' => 'URL',
            'required' => false,
            'display'    => 'Field\InputText'            
        );
    }


    function headerField () {
        return [
            'name' => 'headerIcon',
            'label' => 'Header Icon',
            'display' => 'Field\InputFile'
        ];
    }

    function footerField () {
        return [
            'name' => 'footerIcon',
            'label' => 'Footer Icon',
            'display' => 'Field\InputFile'
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

            <div class="bottom-container">
                {{#if social_links}}
                        {{{ManagerIndexPagination pagination=pagination}}}
                        {{{ManagerIndexButtons metadata=metadata}}}
                        
                        <table class="ui large table segment manager sortable">
                            <col width="10%">
                            <col width="40%">
                            <col width="40%">
                            <col width="10%">
                            <thead>
                                <tr>
                                    <th>Sort</th>
                                    <th>Type</th>
                                    <th>URL</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each social_links}}
                                    <tr data-id="{{dbURI}}">
                                        <td class="handle"><i class="reorder icon"></i></td>
                                        <td>{{{Capitalize type}}}</td>
                                        <td>{{url}}</td>
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
                            {{{ManagerField . class="left" name="type" label="Type" required="true"}}}
                            {{{ManagerField . class="left" name="url" label="URL" required="true"}}}
                            {{{ManagerField . class="left" name="headerIcon" label="Header Icon" required="true"}}}
                            {{{ManagerField . class="left" name="footerIcon" label="Footer Icon" required="true"}}}
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