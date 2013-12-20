<?php
/*
 * @version .8
 * @link https://raw.github.com/virtuecenter/manager/master/available/jobs.php
 * @mode upgrade
 *
 * .6 add categories to list
 * .7 typo
 * .8 definition and description for count added
 */
namespace Manager;

class jobs {
	private $field = false;
    public $collection = 'jobs';
    public $title = 'Jobs';
    public $titleField = 'title';
    public $singular = 'Job';
    public $description = '{{count}} jobs';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main','Contact Information', 'Display Settings', 'SEO'];
    public $icon = 'text file';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'jobs',
        'key' => '_id'
    ];

    public function __construct ($field=false) {
        $this->field = $field;
    }


	function fullNameField () {
		return [
			'name'		=> 'fullName',
			'label'		=> 'Name of Employer or Recruiting Firm',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}
	
	function jobTitleField () {
		return [
			'name'		=> 'title',
			'label'		=> 'Job Title',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	 function imageField () {
        return [
            'name' => 'image',
            'label' => 'Attached Job Description',
            'display' => 'InputFile'
        ];
    }

	function bodyField () {
		return [
			'name' => 'body',
			'required' => false,
			'display' => 'Ckeditor',
			'label'	=> 'Job description'
		];
	}

	function contactNameField () {
		return [
			'name'		=> 'fullName',
			'label'		=> 'Name',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function contactJobTitleField () {
		return [
			'name'		=> 'title',
			'label'		=> 'Job Title',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function emailField () {
		return [
			'name'		=> 'email',
			'label'		=> 'Email',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function phoneField () {
		return [
			'name'		=> 'phone',
			'label'		=> 'Phone',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}


	function addressField () {
		return [
			'name'		=> 'title',
			'label'		=> 'Address',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function cityField () {
		return [
			'name'		=> 'city',
			'label'		=> 'City',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function sectionField () {
		return [
			'name' => 'section',
			'label' => 'States',
			'required' => true,
			'display' => 'InputToTags',
			'multiple' => false,
			'options' => function () {
				$existing = $this->db->distinct('jobs', 'section');
				if (empty($existing)) {
					$existing = [];
				}
				$state_list = array('AL'=>'Alabama',
									'AK'=>'Alaska',
									'AZ'=>'Arizona',
									'AR'=>'Arkansas',
									'CA'=>'California',
									'CO'=>'Colorado',
									'CT'=>'Connecticut',
									'DE'=>'Delaware',
									'DC'=>'District Of Columbia',
									'FL'=>'Florida',
									'GA'=>'Georgia',
									'HI'=>'Hawaii',
									'ID'=>'Idaho',
									'IL'=>'Illinois',
									'IN'=>'Indiana',
									'IA'=>'Iowa',
									'KS'=>'Kansas',
									'KY'=>'Kentucky',
									'LA'=>'Louisiana',
									'ME'=>'Maine',
									'MD'=>'Maryland',
									'MA'=>'Massachusetts',
									'MI'=>'Michigan',
									'MN'=>'Minnesota',
									'MS'=>'Mississippi',
									'MO'=>'Missouri',
									'MT'=>'Montana',
									'NE'=>'Nebraska',
									'NV'=>'Nevada',
									'NH'=>'New Hampshire',
									'NJ'=>'New Jersey',
									'NM'=>'New Mexico',
									'NY'=>'New York',
									'NC'=>'North Carolina',
									'ND'=>'North Dakota',
									'OH'=>'Ohio',
									'OK'=>'Oklahoma',
									'OR'=>'Oregon',
									'PA'=>'Pennsylvania',
									'RI'=>'Rhode Island',
									'SC'=>'South Carolina',
									'SD'=>'South Dakota',
									'TN'=>'Tennessee',
									'TX'=>'Texas',
									'UT'=>'Utah',
									'VT'=>'Vermont',
									'VA'=>'Virginia',
									'WA'=>'Washington',
									'WV'=>'West Virginia',
									'WI'=>'Wisconsin',
									'WY'=>'Wyoming');
				$jobs = array_unique(array_merge($existing, $states_list));
				sort($jobs);
				return $jobs;
			}
		];
	}

	function zipcodeField () {
		return [
			'name'		=> 'zipcode',
			'label'		=> 'Zipcode',
			'required'	=> true,
			'display'	=> 'InputText'
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
                        <col width="90%">
                        <col width="10%">
                        <thead>
                               <tr>
                                  <th>Title</th>
                                  <th class="trash">Delete</th>
                               </tr>
                        </thead>
                        <tbody>
                            {{#each jobs}}
                                <tr data-id="{{dbURI}}">
                                    <td>{{title}}</td>
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
		                    {{#FieldLeft fullName "Name of Employer or Recruiting Firm" required}}{{/FieldLeft}}
		                    {{#FieldLeft title "Job Title" required}}{{/FieldLeft}}
		                    {{#FieldLeft image "File Upload"}}{{/FieldLeft}}
		                    {{#FieldLeft body "Job Description"}}{{/FieldLeft}}
		                    {{{id}}}
		                {{/DocumentFormLeft}}                 
		                
		                {{#DocumentFormRight}}
		                	{{#DocumentButton}}{{/DocumentButton}}
		                	{{#FieldFull status}}{{/FieldFull}}
		                	<br />
		                	{{#FieldFull display_date "Job Expiration Date"}}{{/FieldFull}}
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
		                    {{#FieldLeft fullname Name}}{{/FieldLeft}}
		                    {{#FieldLeft title "Job Title" required}}{{/FieldLeft}}
		                    {{#FieldLeft email Email}}{{/FieldLeft}}
		                    {{#FieldLeft phone Phone}}{{/FieldLeft}}
		                    {{#FieldLeft address Address}}{{/FieldLeft}}
		                    {{#FieldLeft city City}}{{/FieldLeft}}
		                    {{#FieldLeft section States}}{{/FieldLeft}}
		                    {{#FieldLeft zipcode Zipcode}}{{/FieldLeft}}

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