<?php
/*
 * @version .9
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Jobs.php
 * @mode upgrade
 *
 * .6 add categories to list
 * .7 typo
 * .8 definition and description for count added
 * .9 correct title
 */
namespace Manager;

class Jobs {
    private $field = false;
    public $collection = 'jobs';
    public $title = 'Jobs';
    public $titleField = 'job_title';
    public $singular = 'Job';
    public $description = '{{count}} jobs';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Contact Information', 'Display Settings', 'SEO'];
    public $icon = 'text file';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'jobs',
        'key' => '_id'
    ];

    function company_nameField () {
        return [
            'name'         => 'company_name',
            'label'     => 'Name of Employer or Recruiting Firm',
            'required'    => true,
            'display'     => 'InputText'
        ];
    }

    function full_nameField() {
        return [
            'name'        => 'full_name',
            'placeholder' => 'Full Name',
            'display'    => 'InputText',
            'required'     => true
        ];
    }
    
    function job_titleField () {
        return [
            'name'         => 'job_title',
            'label'     => 'Job Title',
            'required'     => true,
            'display'     => 'InputText'
        ];
    }
    
    function contact_personField () {
        return [
            'name'         => 'contact_person',
            'label'     => 'Name',
            'required'     => false,
            'display'     => 'InputText',
        ];
    }
    
    function contact_titleField () {
        return [
            'name'         => 'contact_title',
            'label'     => 'Job Title of Contact',
            'required'     => false,
            'display'     => 'InputText',

        ];
    }
    
    function emailField () {
        return [
            'name'         => 'email',
            'label'     => 'Email',
            'required'     => false,
            'display'     => 'InputText'    
        ];
    }
    
    function job_expirationField() {
        return [
            'name'            => 'job_expiration',
            'label'            => 'Job Expiration Date',
            'required'        => false,
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
    
    function addressField () {
        return [
            'name'         => 'address',
            'label'     => 'Address',
            'required'     => true,
            'display'     => 'InputText'    
        ];
    }
    
    function cityField () {
        return [
            'name'         => 'city',
            'label'     => 'City',
            'required'     => true,
            'display'     => 'InputText'    
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

    function pinnedField () {
        return [
            'name' => 'pinned',
            'label' => 'Pin',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

    function categoriesField () {
        return [
            'name'        => 'categories',
            'label'        => 'Category',
            'required'    => false,
            'options'    => function () {
                return $this->db->fetchAllGrouped(
                    $this->db->collection('categories')->
                        find(['section' => 'Jobs'])->
                        sort(['title' => 1]),
                    '_id', 
                    'title');
            },
            'display'    => 'InputToTags',
            'controlled' => true,
            'multiple' => true
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
                return $this->db->distinct('jobs', 'tags');
            }
        ];
    }

    function stateField () {
        return [
            'name'         => 'state',
            'label'     => 'State',
            'required'     => false,
            'options'     => array(
                'AL'=>"Alabama",
                'AK'=>"Alaska",
                'AS'=>"American Samoa",
                'AZ'=>"Arizona",
                'AR'=>"Arkansas",
                'CA'=>"California",
                'CO'=>"Colorado",
                'CT'=>"Connecticut",
                'DE'=>"Delaware",
                'DC'=>"District Of Columbia",
                'FM'=>"Federated States of Micronesia",
                'FL'=>"Florida",'GA'=>"Georgia",
                'GU'=>"Guam",
                'HI'=>"Hawaii",
                'ID'=>"Idaho",
                'IL'=>"Illinois",
                'IN'=>"Indiana",
                'IA'=>"Iowa",
                'KS'=>"Kansas",
                'KY'=>"Kentucky",
                'LA'=>"Louisiana",
                'ME'=>"Maine",
                'MH'=>"Marshall Islands",
                'MD'=>"Maryland",
                'MA'=>"Massachusetts",
                'MI'=>"Michigan",
                'MN'=>"Minnesota",
                'MS'=>"Mississippi",
                'MO'=>"Missouri",
                'MT'=>"Montana",
                'NE'=>"Nebraska",
                'NV'=>"Nevada",
                'NH'=>"New Hampshire",
                'NJ'=>"New Jersey",
                'NM'=>"New Mexico",
                'NY'=>"New York",
                'NC'=>"North Carolina",
                'ND'=>"North Dakota",
                'MP'=>"Northern Mariana Islands",
                'OH'=>"Ohio",
                'OK'=>"Oklahoma",
                'OR'=>"Oregon",
                'PW'=>"Palau",
                'PA'=>"Pennsylvania",
                'PR'=>"Puerto Rico",
                'RI'=>"Rhode Island",
                'SC'=>"South Carolina",
                'SD'=>"South Dakota",
                'TN'=>"Tennessee",
                'TX'=>"Texas",
                'UT'=>"Utah",
                'VT'=>"Vermont",
                'VI'=>"Virgin Islands",
                'VA'=>"Virginia",
                'WA'=>"Washington",
                'WV'=>"West Virginia",
                'WI'=>"Wisconsin",
                'WY'=>"Wyoming",
                'AA'=>"Armed Forces Americas", 
                'AE'=>"Armed Forces", 
                'AP'=>"Armed Forces Pacific" 
                    ),
                'display'     => 'Select',
                'nullable'     => true
            ];
        }
    
    function zipcodeField () {
        return [
            'name'         => 'zipcode',
            'label'     => 'Zipcode',
            'required'     => true,
            'display'     => 'InputText'    
        ];
    }
    
    function telephoneField () {
        return [
            'name'         => 'telephone',
            'label'     => 'Phone Number',
            'required'     => false,
            'display'     => 'InputText'    
        ];
    }
    
    function websiteField () {
        return [
            'name'         => 'website',
            'label'     => 'Website',
            'required'     => false,
            'display'     => 'InputText'    
        ];
    }
    
    function fileField () {
        return [
            'name'         => 'file',
            'label'     => 'Attached Job Description',
            'required'     => false,
            'display'     => 'InputFile',

        ];
    }
    
    function descriptionField () {
        return [
            'name' => 'description',
            'label' => 'Job Description',
            'display' => 'Ckeditor',
            'required'     => false,
        ];
    }
    
    function display_emailField(){
        return [
            'name'        =>'display_email',
            'label'        => 'Display Email On Listing',
            'required'    => true,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'nullable' => true,
        ];
    }
    
    function display_addressField(){
        return [
            'name'        =>'display_address',
            'label'        => 'Display Address On Listing',
            'required'    => true,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'nullable' => true,

            ];
    }
    
    function display_nameField(){
        return [
            'name'        =>'display_name',
            'label'        => 'Display Name On Listing',
            'required'    => true,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'nullable' => true,

            ];
    }

    function code_nameField () {
        return [
            'name' => 'code_name',
            'display'    => 'InputText'
        ];
    }
    function metakeywordsField () {
        return [
            'name' => 'metadata_keywords',
            'display'    => 'InputText'
        ];
    }

    function metadescriptionField () {
        return [
            'name' => 'metadata_description',
            'display'    => 'InputText'
        ];
    }

    

    public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

           <div class="bottom-container">
              {{#if jobs}}
                    {{#CollectionPagination}}{{/CollectionPagination}}
                    {{#CollectionButtons}}{{/CollectionButtons}}
                
                    <table class="ui large table segment manager sortable">
                        <col width="45%">
                        <col width="25%">
                        <col width="15%">
                        <col width="15%">
                        <col width="10%">
                        <thead>
                               <tr>
                                  <th>Title</th>
                                  <th>Status</th>
                                  <th>Categories</th>
                                  <th>Featured</th>
                                  <th>Pinned</th>
                                  <th class="trash">Delete</th>
                               </tr>
                        </thead>
                        <tbody>
                            {{#each jobs}}
                                <tr data-id="{{dbURI}}">
                                    <td>{{job_title}}</td>
                                    <td>{{#Capitalize}}{{status}}{{/Capitalize}}</td>
                                    <td>{{#CategoriesCSV}}{{categories}}{{/CategoriesCSV}}</td>
                                    <td>{{#BooleanReadable}}{{featured}}{{/BooleanReadable}}</td>
                                    <td>{{#BooleanReadable}}{{pinned}}{{/BooleanReadable}}</td>
                                    <td>
                                       <div class="manager trash ui icon button"><i class="trash icon"></i></div>
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
                            {{#FieldLeft company_name "Name of Employer or Recruiting Firm" required}}{{/FieldLeft}}
                            {{#FieldLeft job_title "Job Title" required}}{{/FieldLeft}}
                            {{#FieldLeft file "Attached Job Description"}}{{/FieldLeft}}
                            {{#FieldLeft description "Job Description"}}{{/FieldLeft}}
                            {{{id}}}
                        {{/DocumentFormLeft}}                 
                        
                        {{#DocumentFormRight}}
                            {{#DocumentButton}}{{/DocumentButton}}
                            {{#FieldFull status}}{{/FieldFull}}
                            <br />
                            {{#FieldFull job_expiration "Job Expiration Date"}}{{/FieldFull}}
                            <div class="ui clearing divider"></div>
                            {{#FieldLeft featured}}{{/FieldLeft}}
                            <br />
                            {{#FieldLeft pinned}}{{/FieldLeft}}
                            <br />
                            {{#FieldFull categories Categories}}{{/FieldFull}}
                            {{#FieldFull tags Tags}}{{/FieldFull}}
                        {{/DocumentFormRight}}
                    </div>
                    <div class="ui tab" data-tab="Contact Information">
                        {{#DocumentFormLeft}}
                            {{#FieldLeft full_name Name}}{{/FieldLeft}}
                            {{#FieldLeft job_title "Job Title" required}}{{/FieldLeft}}
                            {{#FieldLeft email Email}}{{/FieldLeft}}
                            {{#FieldLeft phone Phone}}{{/FieldLeft}}
                            {{#FieldLeft address Address}}{{/FieldLeft}}
                            {{#FieldLeft city City}}{{/FieldLeft}}
                            {{#FieldLeft state State}}{{/FieldLeft}}
                            {{#FieldLeft zipcode Zipcode}}{{/FieldLeft}}
                            {{{id}}}
                        {{/DocumentFormLeft}}                 
                        
                        {{#DocumentFormRight}}
                            {{#DocumentButton}}{{/DocumentButton}}
                        {{/DocumentFormRight}}
                    </div>
                    <div class="ui tab" data-tab="SEO">
                         {{#DocumentFormLeft}}
                            {{#FieldLeft code_name Slug}}{{/FieldLeft}}
                            {{#FieldLeft metadata_description Description}}{{/FieldLeft}}
                              {{#FieldLeft metadata_keywords Keywords}}{{/FieldLeft}}
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