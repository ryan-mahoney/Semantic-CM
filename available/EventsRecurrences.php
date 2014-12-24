<?php
/*
 * @version 2
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsRecurrences.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsRecurrences
{
    public $collection = 'Collection\Events';
    public $title = 'Recurrence Rules';
    public $titleField = 'title';
    public $singular = 'Recurrence Rule';
    public $description = '{{count}} recurrence';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $embedded = true;

    public function whichField()
    {
        return [
            'name'      => 'which',
            'label' => '"Which Scenario?"',
            'required'  => true,
            'options'   => array(
                'published' => '"Day Of Week (Sat - Sun)"',
                'draft'     => '"Number Of Day (1 - 31)"',
            ),
            'display'   => 'Field\Select',
            'nullable'  => false,
            'default'   => ''
        ];
    }

    public function dayOfWeekField()
    {
        return [
            'name'      => 'day_of_week',
            'label' => 'Day Of Week',
            'required'  => true,
            'options' => [
                'sunday'        => "Sunday",
                'monday'        => "Monday",
                'tuesday'       => "Tuesday",
                'wednesday'     => "Wednesday",
                'thursday'      => "Thursday",
                'friday'        => "Friday",
                'saturday'      => "Saturday",
            ],
            'display'   => 'Field\Select',
            'nullable'  => false,
            'default'   => ''
        ];
    }

    public function dayOfWeekTypeField()
    {
        return [
            'name' => 'day_of_week_type',
            'label' => '"Day Of Week Type"',
            'required' => false,
            'options' => [
                'first'     => "First",
                'second'    => "Second",
                'third'     => "Third",
                'fourth'    => "Fourth",
                'fifth'     => "Fifth",
                'last'      => "Last",
            ],
                    'display' => 'Field\Select',
                    'nullable' => true
        ];
    }

    public function monthField()
    {
        return [
            'name' => 'month',
            'label' => '"Day Of Month"',
            'required' => false,
            'options' => [
                       '<select name="month">
                        <option value="1">1</option>
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
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                        </select>',
                        ],
            'display' => 'Field\Select'
            ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedIndexHeader label="Recurrence Rules"}}}
            {{#if recurrence_rules}}
                <table class="ui table manager segment">
                    <thead>
                        <tr>
                            <th>Which</th>
                            <th>Day Of Week Type</th>
                            <th>Month</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each recurrence_rules}}
                            <tr data-id="{{dbURI}}">
                                <td>{{which}}</td>
                                <td>{{day_of_week_type}}</td>
                                <td>{{month}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{{ManagerEmbeddedIndexEmpty singular="Recurrence Rule"}}}
            {{/if}}
HBS;

        return $partial;
    }

    public function formPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedFormHeader metadata=metadata}}}
                {{{ManagerField . class="fluid" name="which" label="Which Scenario?"}}}
                {{{ManagerField . class="fluid" name="day_of_week" label="Day Of Week"}}}
                {{{ManagerField . class="fluid" name="day_of_week_type" label="Day Of Week Type"}}}
                {{{ManagerField . class="fluid" name="month" label="Day Of Month"}}}
                {{{id}}}
                {{{form-token}}}
            {{{ManagerEmbeddedFormFooter}}}
            <div style="padding-bottom:100px"></div>
HBS;

        return $partial;
    }
}
