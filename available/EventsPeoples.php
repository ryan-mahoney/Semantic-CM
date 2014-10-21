<?php
/*
 * @version 2
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsPeoples.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsPeoples {
    public $collection = 'Collection\Events';
    public $title = 'Peoples';
    public $titleField = 'title';
    public $singular = 'People';
    public $description = '{{count}} people';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $embedded = true;

    function first_nameField() {
        return [
          'name'    => 'first_name',
          'placeholder' => 'First Name',
          'display' => 'Field\InputText',
          'required'  => true
        ];
    }

    function last_nameField() {
        return [
          'name'    => 'last_name',
          'placeholder' => 'Last Name',
          'label'   => 'Last Name',
          'display' => 'Field\InputText',
          'required'  => true
        ];
    }

    function emailField () {
        return [
            'name'      => 'email',
            'label'     => 'Email',
            'required'  => true,
            'display'   => 'Field\InputText'
        ];
    }

    function phoneField () {
        return [
            'name'      => 'phone',
            'label'     => 'Phone',
            'required'  => true,
            'display'   => 'Field\InputText'
        ];
    }

    function roleField () {
        return [
            'name'      => 'role',
            'label'     => 'Role',
            'required'  => true,
            'display'   => 'Field\InputText'
        ];
    }

    function bioField () {
        return [
            'display' => 'Field\Redactor',
            'name' => 'bio'
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedIndexHeader label="Peoples"}}}
            {{#if people_sub}}
                <table class="ui table manager segment">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each people_sub}}
                            <tr data-id="{{dbURI}}">
                                <td>{{first_name}}</td>
                                <td>{{role}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{{ManagerEmbeddedIndexEmpty singular="People"}}}
            {{/if}}
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedFormHeader metadata=metadata}}}
                {{{ManagerField . class="fluid" name="first_name" label="First Name"}}}
                {{{ManagerField . class="fluid" name="last_name" label="Last Name"}}}
                {{{ManagerField . class="fluid" name="email" label="Email"}}}
                {{{ManagerField . class="fluid" name="phone" label="Phone"}}}
                {{{ManagerField . class="fluid" name="role" label="Role"}}}
                {{{ManagerField . class="fluid" name="bio" label="Bio"}}}
                {{{id}}}
                {{{form-token}}}
            {{{ManagerEmbeddedFormFooter}}}
            <div style="padding-bottom:100px"></div>
HBS;
        return $partial;
    }
}