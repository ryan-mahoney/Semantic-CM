<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/DepartmentProfiles.php
 * @mode upgrade
 * .3 definiton and description for count adde
 * .4 fixes
 */
namespace Manager;

class DepartmentProfiles
{
    public $collection = 'Collection\DepartmentProfiles';
    public $title = 'Profiles';
    public $titleField = 'title';
    public $singular = 'Profile';
    public $description = '{{count}} profiles';
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
            'name'     => 'title',
            'label'    => 'Title',
            'required' => false,
            'display'  => 'Field\InputText'
        ];
    }

    public function imageField()
    {
        return [
            'name'    => 'image',
            'label'   => 'Image',
            'display' => 'Field\InputFile'
        ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedIndexHeader label="Profiles"}}}
            {{#if department_profiles}}
                <table class="ui table manager segment">
                    <thead>
                        <tr><th>Title</th></tr>
                    </thead>
                    <tbody>
                        {{#each department_profiles}}
                            <tr data-id="{{dbURI}}">
                                <td>{{title}}</td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{{ManagerEmbeddedIndexEmpty singular="Profile"}}}
            {{/if}}
HBS;

        return $partial;
    }

    public function formPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedFormHeader metadata=metadata}}}
                {{{ManagerField . class="fluid" name="title" label="Title"}}}
                {{{ManagerField . class="fluid" name="image" label="Image"}}}
                {{{id}}}
                {{{form-token}}}
            {{{ManagerEmbeddedFormFooter}}}
HBS;

        return $partial;
    }
}
