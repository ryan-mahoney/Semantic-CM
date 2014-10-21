<?php
/*
 * @version 2
 * @link https://raw.github.com/virtuecenter/manager/master/available/UsersAddress.php
 * @mode upgrade
 *
 */
namespace Manager;

class UsersAddress {
    public $collection = 'Collection\Users';
    public $title = 'address';
    public $titleField = 'title';
    public $singular = 'Address';
    public $description = '{{count}} address';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $notice = 'Address Saved';
    public $embedded = true;

    function typeField () {
        return [
            'name'      => 'type',
            'required'  => true,
            'options'   => array(
                'home'  => 'Home',
                'work'      => 'Work'
            ),
            'display'   => 'Field\Select',
            'nullable'  => false,
            'default'   => 'blank'
        ];
    }

    function addressField () {
        return [
            'name' => 'address',
            'label' => 'Address',
            'required' => false,
            'display' => 'Field\Textarea'
        ];
    }

    function address2Field () {
        return [
            'name' => 'address2',
            'label' => 'Address2',
            'required' => false,
            'display' => 'Field\Textarea'
        ];
    }

    function cityField () {
        return [
            'name'      => 'city',
            'label'     => 'City',
            'required'  => true,
            'display'   => 'Field\InputText'  
        ];
    }

    function stateField () {
        return [
            'name'      => 'state',
            'label'     => 'State',
            'required'  => false,
            'options'   => array(
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
                'display'   => 'Field\Select',
                'nullable'  => true
            ];
        }
    
    function zipcodeField () {
        return [
            'name'      => 'zip',
            'label'     => 'Zip',
            'required'  => true,
            'display'   => 'Field\InputText'  
        ];
    }

    function primaryField () {
        return [
            'name' => 'primary',
            'label' => 'Primary',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'Field\InputSlider',
            'default' => 'f'
        ];
    }


    public function indexPartial () {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedIndexHeader label="Address"}}}
            {{#if address_sub}}
                <table class="ui table manager segment">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>State</th>
                            <th>Primary</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each address_sub}}
                            <tr data-id="{{dbURI}}">
                                <td>{{type}}</td>
                                <td>{{state}}</td>
                                <td>{{primary}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{{ManagerEmbeddedIndexEmpty singular="Address"}}}
            {{/if}}
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedFormHeader metadata=metadata}}}
                {{{ManagerField . class="fluid" name="type" label="Type"}}}
                {{{ManagerField . class="fluid" name="address" label="Address"}}}
                {{{ManagerField . class="fluid" name="address2" label="Address2"}}}
                {{{ManagerField . class="fluid" name="city" label="City"}}}
                {{{ManagerField . class="fluid" name="state" label="State"}}}
                {{{ManagerField . class="fluid" name="zip" label="Zip"}}}
                {{{ManagerField . class="fluid" name="primary" label="Primary"}}}
                {{{id}}}
                {{{form-token}}}
            {{{ManagerEmbeddedFormFooter}}}
HBS;
        return $partial;
    }
}