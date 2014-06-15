<?php
/*
 * @version .8
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Carousels.php
 * @mode upgrade
 *
 *
 * .3 duplicate subcarousels field
 * .4 field name issue
 * .6 sort removed
 * .7 trash smaller
 * .8 definiton and description for count added 
 * .9 name attributes
 */
namespace Manager;

class Carousels {
    private $field = false;
    public $collection = 'carousels';
    public $title = 'Carousel';
    public $titleField = 'title';
    public $singular = 'Carousel';
    public $description = '{{count}} carousels';
    public $definition = 'A carousel is a rotating photo module that most typically appears on a homepage.';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'sign in';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $notice = 'Carousel Saved';
    public $storage = [
        'collection' => 'carousels',
        'key' => '_id'
    ];

    function titleField () {    
        return [
            'name' => 'title',
            'label' => 'Title',
            'required' => true,
            'display' => 'InputText'
        ];
    }
    
    function descriptionField () {
        return [
            'name' => 'description',
            'label' => 'Description',
            'display' => 'Textarea'
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
                return $this->db->distinct('carousels', 'tags');
            }
        ];
    }    

    public function carousel_individualField() {
        return [
            'name'        => 'carousel_individual',
            'label'        => 'carousels',
            'required'    => false,
            'display'    => 'Manager',
            'manager'    => 'subcarousels'
        ];
    }
    
    public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#if carousels}}
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
                            {{#each carousels}}
                                <tr data-id="{{dbURI}}">
                                   
                                    <td>{{title}}</td>
                                    <td>{{#ArrayToCSV}}{{tags}}{{/ArrayToCSV}}</td>
                                    <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
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
                    {{#DocumentFormLeft}}
                        {{#FieldLeft title Title required}}{{/FieldLeft}}
                        {{#FieldLeft description Description required}}{{/FieldLeft}}
                        {{#FieldEmbedded field="carousel_individual" manager="subcarousels" Frames}}
                        {{{id}}}
                    {{/DocumentFormLeft}}                 
                    
                    {{#DocumentFormRight}}
                        {{#DocumentButton}}{{/DocumentButton}}
                        {{#FieldFull tags Tags}}{{/FieldFull}}
                    {{/DocumentFormRight}}
                </div>
            </form>
HBS;
        return $partial;
    }
}    