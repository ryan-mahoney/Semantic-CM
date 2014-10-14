<?php
/*
 * @version .7
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Testimonials.php
 * @mode upgrade
 *
 * .4 use name not title
 * .5 remove sort
 * .6 typo
 * .7 definition and description for count added
 */
namespace Manager;

class Testimonials {
    private $field = false;
    public $collection = 'testimonials';
    public $title = 'Testimonials';
    public $titleField = 'name';
    public $singular = 'Testimonial';
    public $description = '{{count}} testimonials';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images'];
    public $icon = 'chat';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'testimonials',
        'key' => '_id'
    ];

    function titleField () {
        return [
            'name'        => 'name',
            'label'        => 'Name',
            'required'    => true,
            'display'    => 'InputText'
        ];
    }

    function locationField(){
        return [
            'name'=>'location',
            'label'=>'Location',
            'display'    => 'InputText'
        ];
    }
    
    function occupationField(){
        return array(
            'name'=>'occupation',
            'label'=>'Occupation',
            'display'    => 'InputText'
        );
    }    

    function messageField(){
        return [
            'name'=>'message',
            'label'=>'Message',
            'display' => 'Ckeditor',
        ];
    }

    function messageshortField(){
        return [
            'name'=>'messageshort',
            'label'=>'"Short Message"',
            'display' => 'Ckeditor',
        ];
    }

    function imageField () {
        return [
            'name' => 'image',
            'label' => 'Image',
            'display' => 'InputFile'
        ];
    }

    function statusField () {
        return [
            'name'        => 'status',
            'required'    => true,
            'options'    => array(
                'published'    => 'Published',
                'draft'        => 'Draft'
            ),
            'display'    => 'Select',
            'nullable'    => false,
            'default'    => 'published'
        ];
    }

    function dateField() {
        return [
            'name'            => 'display_date',
            'required'        => true,
            'display'        => 'InputDatePicker',
            'transformIn'    => function ($data) {
                return new \MongoDate(strtotime($data));
            },
            'transformOut'    => function ($data) {
                return date('m/d/Y', $data->sec);
            },
            'default'        => function () {
                return date('m/d/Y');
            }
        ];
    }

    function featuredField () {
        return [
            'name' => 'featured',
            'label' => 'Feature',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

    function approvedField () {
        return [
            'name' => 'approved',
            'label' => false,
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
        ),
            'display' => 'InputRadioButton',
            'default' => 'f'
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#if testimonials}}
                        {{#CollectionPagination}}{{/CollectionPagination}}
                        {{#CollectionButtons}}{{/CollectionButtons}}
                        
                        <table class="ui large table segment manager sortable">
                                <col width="40%">
                                <col width="30%">
                                <col width="20%">
                                <col width="10%">
                            <thead>
                                <tr>
                                   
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th class="trash">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each testimonials}}
                                    <tr data-id="{{dbURI}}">
                                        
                                        <td>{{title}}</td>
                                        <td>{{#Capitalize}}{{status}}{{/Capitalize}}</td>
                                        <td>{{#BooleanReadable}}{{featured}}{{/BooleanReadable}}</td>
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
                            {{#FieldLeft title Name required}}{{/FieldLeft}}
                            {{#FieldLeft location Location required}}{{/FieldLeft}}
                            {{#FieldLeft occupation Occupation}}{{/FieldLeft}}
                            {{#FieldLeft message Message}}{{/FieldLeft}}
                            {{#FieldLeft messageshort "Short Message"}}{{/FieldLeft}}
                            {{{id}}}
                        {{/DocumentFormLeft}}                 
                        
                        {{#DocumentFormRight}}
                            {{#DocumentButton}}{{/DocumentButton}}
                            {{#FieldFull status}}{{/FieldFull}}
                            <br />
                            {{#FieldFull display_date}}{{/FieldFull}}
                            <div class="ui clearing divider"></div>
                            {{#FieldLeft featured}}{{/FieldLeft}}
                            <br />
                            {{#FieldLeft approved}}{{/FieldLeft}}
                        {{/DocumentFormRight}}
                    </div>

                     <div class="ui tab" data-tab="Images">
                        {{#DocumentFormLeft}}
                            {{#FieldLeft image Image}}{{/FieldLeft}}
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