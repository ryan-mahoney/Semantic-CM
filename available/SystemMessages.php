<?php
/*
 * @version .3
 * @link https://raw.github.com/virtuecenter/manager/master/available/SystemMessages.php
 * @mode upgrade
 * 
 */
namespace Manager;

class SystemMessages {
	private $field = false;
    public $collection = 'system_messages';
    public $title = 'System Messages';
    public $titleField = 'name';
    public $singular = 'Message';
    public $description = '{{count}} messages';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Content'];
    public $icon = 'text file';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'system_messages',
        'key' => '_id'
    ];

	  function nameField() {
        return [
            'name'    => 'name',
            'placeholder' => 'Name',
            'display' => 'InputText',
            'required'  => true
        ];
    }
  
  function subjectField() {
    return [
      'name'    => 'subject',
      'placeholder' => 'Subject',
      'label'   => 'Subject',
      'display' => 'InputText',
      'required'  => true
    ];
  }

	function bodyField () {
    return [
      'name' => 'body',
      'label' => 'Body',
      'required' => false,
      'display' => 'Ckeditor'
    ];
  }

  function fromField() {
    return [
      'name'    => 'from',
      'placeholder' => 'From',
      'display' => 'InputToTags',
      'controlled' => false,
      'multiple' => false,
      'required'  => true,
      'options' => function () {
        return $this->db->distinct('system_messages', 'from');
      }
    ];
  }  

  function replyToField() {
    return [
      'name'    => 'replyTo',
      'placeholder' => 'Reply To',
      'display' => 'InputToTags',
      'controlled' => false,
      'multiple' => false,
      'required'  => false,
      'options' => function () {
        return $this->db->distinct('system_messages', 'replyTo');
      }
    ];
  }

  function ccField() {
    return [
      'name'    => 'cc',
      'placeholder' => 'CC',
      'display' => 'InputToTags',
      'multiple' => true,
      'controlled' => false,
      'required'  => false,
      'options' => function () {
        return $this->db->distinct('system_messages', 'cc');
      }
    ];
  }

  function bccField() {
    return [
      'name'    => 'bcc',
      'placeholder' => 'Bcc',
      'display' => 'InputToTags',
      'multiple' => true,
      'controlled' => false,
      'required'  => false,
      'options' => function () {
        return $this->db->distinct('system_messages', 'Bcc');
      }
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
      'controlled' => false,
      'options' => function () {
        return $this->db->distinct('system_messages', 'tags');
      }
    ];
  }

    public function indexPartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

           <div class="bottom-container">
              {{#if system_messages}}
                    {{#CollectionPagination}}{{/CollectionPagination}}
                    {{#CollectionButtons}}{{/CollectionButtons}}
                
                    <table class="ui large table segment manager">
                         <thead>
                               <tr>
                                  <th>Name</th>
                                  <th>Subject</th>
                                  <th>Reply To</th>
                                  <th class="trash">Delete</th>
                               </tr>
                         </thead>
                         <tbody>
                            {{#each system_messages}}
                                 <tr data-id="{{dbURI}}">
                                     <td>{{name}}</td>
                                     <td>{{subject}}</td>
                                      <td>{{replyTo}}</td>
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
	            	<div class="ui tab active" data-tab="Content">
		                {{#DocumentFormLeft}}
                        {{#FieldLeft name Name}}{{/FieldLeft}}
                        {{#FieldLeft subject Subject}}{{/FieldLeft}}
		                    {{#FieldLeft body Body}}{{/FieldLeft}}
		                    {{{id}}}
		                {{/DocumentFormLeft}}                 
		                
		                {{#DocumentFormRight}}
		                	{{#DocumentButton}}{{/DocumentButton}}
		                	{{#FieldFull from From}}{{/FieldFull}}
                      {{#FieldFull replyTo "Reply To"}}{{/FieldFull}}
                      {{#FieldFull cc CC}}{{/FieldFull}}
                      {{#FieldFull bcc Bcc}}{{/FieldFull}}
		                	<div class="ui clearing divider"></div>
                      {{#FieldFull tags Tags}}{{/FieldFull}}
		                {{/DocumentFormRight}}
		            </div>	       
	            </div>
	        </form>
HBS;
        return $partial;
    }
}	