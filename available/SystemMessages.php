<?php
/*
 * @version 2
 * @link https://raw.github.com/virtuecenter/manager/master/available/SystemMessages.php
 * @mode upgrade
 * 
 */
namespace Manager;

class SystemMessages {
    public $collection = 'Collection\SystemMessages';
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

    function nameField() {
        return [
            'name'    => 'name',
            'placeholder' => 'Name',
            'display' => 'Field\InputText',
            'required'  => true
        ];
    }
  
    function subjectField() {
        return [
            'name'    => 'subject',
            'placeholder' => 'Subject',
            'display' => 'Field\InputText',
            'required'  => true
        ];
    }

    function bodyField () {
        return [
            'name' => 'body',
            'required' => false,
            'display' => 'Field\Redactor'
        ];
    }

    function fromField() {
        return [
            'name'    => 'from',
            'placeholder' => 'From',
            'display' => 'Field\InputToTags',
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
      'name'    => 'reply_to',
      'placeholder' => 'Reply To',
      'display' => 'Field\InputToTags',
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
      'display' => 'Field\InputToTags',
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
      'display' => 'Field\InputToTags',
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
      'display' => 'Field\InputToTags',
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
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

           <div class="bottom-container">
              {{#if system_messages}}
                    {{{ManagerIndexPagination pagination=pagination}}}
                    {{{ManagerIndexButtons metadata=metadata}}}
                
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
                    <div class="ui tab active" data-tab="Content">
                        {{{ManagerFormMainColumn}}}
                        {{{ManagerField . class="left" name="name" label="Name"}}}
                        {{{ManagerField . class="left" name="subject" label="Subject"}}}
                            {{{ManagerField . class="left" name="body" label="Body"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}                 
                        
                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="fluid" name="from" label="From"}}}
                            {{{ManagerField . class="fluid" name="reply_to" label="Reply To"}}}
                            {{{ManagerField . class="fluid" name="cc" label="CC"}}}
                            {{{ManagerField . class="fluid" name="bcc" label="Bcc"}}}
                            <div class="ui clearing divider"></div>
                            {{{ManagerField . class="fluid" name="tags" label="Tags"}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>         
                </div>
            </form>
HBS;
        return $partial;
    }
}   