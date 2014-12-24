<?php
/*
 * @version 2
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsRegistrations.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsRegistrations
{
    public $collection = 'Collection\Events';
    public $title = 'Registrations Options';
    public $titleField = 'title';
    public $singular = 'Registration Options';
    public $description = '{{count}} registrations';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $embedded = true;

    public function titleField()
    {
        return [
            'name'      => 'title',
            'label'     => 'Name',
            'required'  => true,
            'display'   => 'Field\InputText'
        ];
    }

    public function descriptionField()
    {
        return [
            'name' => 'description',
            'label' => 'Summary',
            'display' => 'Field\Redactor'
        ];
    }

    public function costField()
    {
        return [
            'name' => 'price',
            'label' => 'Cost',
            'required' => false,
            'display' => 'Field\InputText'
        ];
    }

    public function maximumUnitsPerCustomerField()
    {
        return [
            'name' => 'maximum_units_per_customer',
            'label' => '"Maximum Units Per Customer"',
            'required' => false,
            'options' => function () {
                $options = '';
                for ($i = 1; $i <= 100; $i++) {
                    $options .= "<option value='".$i."'>".$i."</option>";
                }

                return $options;
            },
            'display' => 'Field\Select'
        ];
    }

    public function quantityField()
    {
        return [
            'name' => 'quantity',
            'label' => '"Attendees Per Item"',
            'required' => false,
            'options' => [
                       '<option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>',
                        ],
            'display' => 'Field\Select'
            ];
    }

    public function chooseFormField()
    {
        return [
            'name' => 'form',
            'label' => 'Form',
            'required' => false,
            'options' => [
                '<option value="Choose Form">1</option>',
                '<option value="Choose Form">2</option>',
                '<option value="Choose Form">3</option>',
            ],
            'display' => 'Field\Select'
        ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedIndexHeader label="Registration Options"}}}
            {{#if registration_options}}
                <table class="ui table manager segment">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each registration_options}}
                            <tr data-id="{{dbURI}}">
                                <td>{{title}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{{ManagerEmbeddedIndexEmpty singular="Registration Options"}}}
            {{/if}}
HBS;

        return $partial;
    }

    public function formPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedFormHeader metadata=metadata}}}
                {{{ManagerField . class="fluid" name="title" label="Title"}}}
                {{{ManagerField . class="fluid" name="description" label="Summary"}}}
                {{{ManagerField . class="fluid" name="price" label="Cost"}}}
                {{{ManagerField . class="fluid" name="maximum_units_per_customer" label="Maximum Units Per Customer"}}}
                {{{ManagerField . class="fluid" name="quantity" label="Attendees Per Item"}}}
                {{{ManagerField . class="fluid" name="form" label="Form"}}}
                {{{id}}}
                {{{form-token}}}
            {{{ManagerEmbeddedFormFooter}}}
            <div style="padding-bottom:100px"></div>
HBS;

        return $partial;
    }
}
