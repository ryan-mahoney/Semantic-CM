<?php
/*
 * @version .9
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/FileUploads.php
 * @mode upgrade
 *
 * .5 add title
 * .6 add title for form
 * .7 resolve conflict
 * .8 remove sort
 * .9 definition  and description for count added
 */
namespace Manager;

class FileUploads {
    private $field = false;
    public $collection = 'file_uploads';
    public $title = 'File Uploads';
    public $titleField = 'title';
    public $singular = 'File Upload';
    public $description = '{{count}} file uploads';
    public $definition = 'Files that visitors can download from your site, such as high resolution images, PDF\'s, and white papers, can be uploaded here. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main'];
    public $icon = 'cloud upload';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'file_uploads',
        'key' => '_id'
    ];

    function titleField () {
        return [
            'name'      => 'title',
            'label'     => 'Title',
            'required'  => true,
            'display'   => 'InputText'
        ];
    }
    
    function imageField () {
        return [
            'name' => 'image',
            'label' => 'File Upload',
            'display' => 'InputFile'
        ];
    }

    public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#if file_uploads}}
                        {{#CollectionPagination}}{{/CollectionPagination}}
                        {{#CollectionButtons}}{{/CollectionButtons}}
                        
                        <table class="ui large table segment manager sortable">
                            <col width="60%">
                            <col width="20%">
                            <col width="20%">
                            <thead>
                                <tr>
                                    
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each file_uploads}}
                                    <tr data-id="{{dbURI}}">
                                         
                                         <td>{{title}}</td>
                                         <td>{{image.url}}</td>
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
                            {{#FieldLeft title Title required}}{{/FieldLeft}}
                            {{#FieldLeft image "File Upload" required}}{{/FieldLeft}}
                            {{#FieldLeft image "File Upload" required}}{{/FieldLeft}}
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