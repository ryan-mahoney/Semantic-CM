<?php
/*
 * @version 2
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
    public $collection = 'Collection\Jobs';
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

    function company_nameField () {
        return [
            'name'         => 'company_name',
            'label'     => 'Name of Employer or Recruiting Firm',
            'required'    => true,
            'display'     => 'Field\InputText'
        ];
    }

    function full_nameField() {
        return [
            'name'        => 'full_name',
            'placeholder' => 'Full Name',
            'display'    => 'Field\InputText',
            'required'     => true
        ];
    }
    
    function job_titleField () {
        return [
            'name'         => 'job_title',
            'label'     => 'Job Title',
            'required'     => true,
            'display'     => 'Field\InputText'
        ];
    }
    
    function contact_personField () {
        return [
            'name'         => 'contact_person',
            'label'     => 'Name',
            'required'     => false,
            'display'     => 'Field\InputText',
        ];
    }
    
    function contact_titleField () {
        return [
            'name'         => 'contact_title',
            'label'     => 'Job Title of Contact',
            'required'     => false,
            'display'     => 'Field\InputText',

        ];
    }
    
    function emailField () {
        return [
            'name'         => 'email',
            'label'     => 'Email',
            'required'     => false,
            'display'     => 'Field\InputText'    
        ];
    }
    
    function job_expirationField() {
        return [
            'name'            => 'job_expiration',
            'label'            => 'Job Expiration Date',
            'required'        => false,
            'display'        => 'Field\InputDatePicker',
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
            'display'     => 'Field\InputText'    
        ];
    }
    
    function cityField () {
        return [
            'name'         => 'city',
            'label'     => 'City',
            'required'     => true,
            'display'     => 'Field\InputText'    
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
            'display'    => 'Field\Select',
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
            'display' => 'Field\InputSlider',
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
            'display' => 'Field\InputSlider',
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
            'display'    => 'Field\InputToTags',
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
            'display' => 'Field\InputToTags',
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
                'display'     => 'Field\Select',
                'nullable'     => true
            ];
        }
    
    function zipcodeField () {
        return [
            'name'         => 'zipcode',
            'label'     => 'Zipcode',
            'required'     => true,
            'display'     => 'Field\InputText'    
        ];
    }
    
    function telephoneField () {
        return [
            'name'         => 'telephone',
            'label'     => 'Phone Number',
            'required'     => false,
            'display'     => 'Field\InputText'    
        ];
    }
    
    function websiteField () {
        return [
            'name'         => 'website',
            'label'     => 'Website',
            'required'     => false,
            'display'     => 'Field\InputText'    
        ];
    }
    
    function fileField () {
        return [
            'name'         => 'file',
            'label'     => 'Attached Job Description',
            'required'     => false,
            'display'     => 'Field\InputFile',

        ];
    }
    
    function descriptionField () {
        return [
            'name' => 'description',
            'label' => 'Job Description',
            'display' => 'Field\Redactor',
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
            'display' => 'Field\InputSlider',
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
            'display' => 'Field\InputSlider',
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
            'display' => 'Field\InputSlider',
            'nullable' => true,

            ];
    }

    function code_nameField () {
        return [
            'name' => 'code_name',
            'display'    => 'Field\InputText'
        ];
    }
    function metakeywordsField () {
        return [
            'name' => 'metadata_keywords',
            'display'    => 'Field\InputText'
        ];
    }

    function metadescriptionField () {
        return [
            'name' => 'metadata_description',
            'display'    => 'Field\InputText'
        ];
    }

    

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

           <div class="bottom-container">
              {{#if jobs}}
                    {{{ManagerIndexPagination pagination=pagination}}}
                    {{{ManagerIndexButtons metadata=metadata}}}
                
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
                                    <td>{{{Capitalize status}}}</td>
                                    <td>{{{CategoriesCSV categories}}}</td>
                                    <td>{{{BooleanReadable featured}}}</td>
                                    <td>{{{BooleanReadable pinned}}}</td>
                                    <td>
                                       <div class="manager trash ui icon button"><i class="trash icon"></i></div>
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
                            {{{ManagerField . class="left" name="company_name" label="Name of Employer or Recruiting Firm" required="true"}}}
                            {{{ManagerField . class="left" name="job_title" label="Job Title" required="true"}}}
                            {{{ManagerField . class="left" name="file" label="Attached Job Description"}}}
                            {{{ManagerField . class="left" name="description" label="Job Description"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}                 
                        
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="fluid" name="status"}}}
                            <br />
                            {{{ManagerField . class="fluid" name="job_expiration" label="Job Expiration Date"}}}
                            <div class="ui clearing divider"></div>
                            {{{ManagerField . class="left" name="featured"}}}
                            <br />
                            {{{ManagerField . class="left" name="pinned"}}}
                            <br />
                            {{{ManagerField . class="fluid" name="categories" label="Categories"}}}
                            {{{ManagerField . class="fluid" name="tags" label="Tags"}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                    <div class="ui tab" data-tab="Contact Information">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . class="left" name="full_name" label="Name"}}}
                            {{{ManagerField . class="left" name="job_title" label="Job Title" required="true"}}}
                            {{{ManagerField . class="left" name="email" label="Email"}}}
                            {{{ManagerField . class="left" name="phone" label="Phone"}}}
                            {{{ManagerField . class="left" name="address" label="Address"}}}
                            {{{ManagerField . class="left" name="city" label="City"}}}
                            {{{ManagerField . class="left" name="state" label="State"}}}
                            {{{ManagerField . class="left" name="zipcode" label="Zipcode"}}}
                        {{{ManagerFormMainColumnClose}}}                 
                        
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>
                    <div class="ui tab" data-tab="SEO">
                         {{{ManagerFormMainColumn}}}
                            {{{ManagerField . class="left" name="code_name" label="Slug"}}}
                            {{{ManagerField . class="left" name="metadata_description" label="Description"}}}
                            {{{ManagerField . class="left" name="metadata_keywords" label="Keywords"}}}
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