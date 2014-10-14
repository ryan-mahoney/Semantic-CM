<?php
/*
 * @version .7
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Blurbs.php
 * @mode upgrade
 *
 * .5 show tags on list
 * .6 sort removed
 * .7 definition and description for count added
 */
namespace Manager;

class Blurbs {
    private $field = false;
    public $collection = 'blurbs';
    public $title = 'Blurbs';
    public $titleField = 'title';
    public $singular = 'Blurb';
    public $description = '{{count}} blurbs';
    public $definition = 'Blurbs are small blocks of content that don\'t fit the conventions of a whole web page. Many appear on multiple pages in the same format. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'basic content';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'blurbs',
        'key' => '_id'
    ];
    
    function titleField () {
        return [
            'name' => 'title',
            'placeholder' => 'Title',
            'required' => true,
            'display' => 'InputText'
        ];
    }

    function bodyField () {
        return [
            'name' => 'body',
            'required' => false,
            'display' => 'Ckeditor'        
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
            'display' => 'InputToTags',
            'multiple' => true,
            'options' => function () {
                return $this->db->distinct('blurbs', 'tags');
            }
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#if blurbs}}
                    {{#CollectionPagination}}{{/CollectionPagination}}
                    {{#CollectionButtons}}{{/CollectionButtons}}
                    
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
                               {{#each blurbs}}
                                <tr data-id="{{dbURI}}">
                                    
                                    <td>{{title}}</td>
                                    <td>{{#ArrayToCSV}}{{tags}}{{/ArrayToCSV}}</td>
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
                            {{#FieldLeft body required}}{{/FieldLeft}}
                            {{{id}}}
                        {{/DocumentFormLeft}}
                        {{#DocumentFormRight}}
                            {{#DocumentButton}}{{/DocumentButton}}
                            {{#FieldFull tags Tags}}{{/FieldFull}}
                        {{/DocumentFormRight}}
                    </div>
                </div>
            </form>
HBS;
        return $partial;
    }
}