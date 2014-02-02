<?php
/*
 * @version .8
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/social_links.php
 * @mode upgrade
 *
 * .4 lacks title field
 * .5 list view does not make sense
 * .6 fix conflict
 * .7 remove sort
 * .8 definition and description for count added
 */
namespace Manager;

class social_links {
    private $field = false;
    public $collection = 'social_links';
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
    public $storage = [
        'collection' => 'social_links',
        'key' => '_id'
    ];
    
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
                'display' => 'Select',
                'nullable' => true
        ];
    }    
    
    function urlField () {
        return array(
            'name' => 'url',
            'label' => 'URL',
            'required' => false,
            'display'    => 'InputText'            
        );
    }


    function headerField () {
        return [
            'name' => 'headerIcon',
            'label' => 'Header Icon',
            'display' => 'InputFile'
        ];
    }

    function footerField () {
        return [
            'name' => 'footerIcon',
            'label' => 'Footer Icon',
            'display' => 'InputFile'
        ];
    }

    public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#if social_links}}
                        {{#CollectionPagination}}{{/CollectionPagination}}
                        {{#CollectionButtons}}{{/CollectionButtons}}
                        
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
                                        <td>{{#Capitalize}}{{type}}{{/Capitalize}}</td>
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

                        {{#CollectionPagination}}{{/CollectionPagination}}
                   {{else}}
                    {{#CollectionEmpty}}{{/CollectionEmpty}}
                {{/if}}
            </div>
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            {{#Form}}{{/Form}}
                <div class="top-container">
                    {{#DocumentHeader}}{{/DocumentHeader}}
                    {{#DocumentTabs}}{{/DocumentTabs}}
                </div>
                <div class="bottom-container">
                     <div class="ui tab active" data-tab="Main">
                         {{#DocumentFormLeft}}
                             {{#FieldLeft type Type required}}{{/FieldLeft}}
                             {{#FieldLeft url URL required}}{{/FieldLeft}}
                             {{#FieldLeft headerIcon "Header Icon" required}}{{/FieldLeft}}
                             {{#FieldLeft footerIcon "Footer Icon" required}}{{/FieldLeft}}
                             {{{id}}}
                         {{/DocumentFormLeft}}                 
                    
                         {{#DocumentFormRight}}
                            {{#DocumentButton}}{{/DocumentButton}}
                         {{/DocumentFormRight}}
                     </div>
                </div>
            </form>
HBS;
        return $partial;
    }
}