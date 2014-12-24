<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Profiles.php
 * @mode upgrade
 * .3 definition and description for count added
 */
namespace Manager;

class Profiles
{
    public $collection = 'Collection\Profiles';
    public $title = 'Profiles';
    public $titleField = 'title';
    public $singular = 'Profile';
    public $description = '{{count}} profiles';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images'];
    public $icon = 'text file';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';

    public function first_nameField()
    {
        return [
            'name'    => 'first_name',
            'placeholder' => 'First Name',
            'display' => 'Field\InputText',
            'required'  => true
        ];
    }

    public function last_nameField()
    {
        return [
            'name'    => 'last_name',
            'placeholder' => 'Last Name',
            'label'   => 'Last Name',
            'display' => 'Field\InputText',
            'required'  => true
        ];
    }

    public function fullNameField()
    {
        return [
            'name'        => 'full_name',
            'label'        => 'FullName',
            'required'    => true,
            'display'    => 'Field\InputText'
        ];
    }

    public function titleField()
    {
        return [
            'name'        => 'title',
            'label'        => 'Title',
            'required'    => true,
            'display'    => 'Field\InputText'
        ];
    }

    public function emailField()
    {
        return [
            'name'        => 'email',
            'label'        => 'Email',
            'required'    => true,
            'display'    => 'Field\InputText'
        ];
    }

    public function homepageField()
    {
        return [
            'display' => 'Field\InputText',
            'name' => 'homepage'
        ];
    }

    public function phoneField()
    {
        return [
            'name'        => 'phone',
            'label'        => 'Phone',
            'required'    => true,
            'display'    => 'Field\InputText'
        ];
    }

    public function descriptionField()
    {
        return [
            'name' => 'description',
            'label' => 'Summary',
            'display' => 'Field\Textarea'
        ];
    }

    public function statusField()
    {
        return [
            'name'        => 'status',
            'required'    => true,
            'options'    => array(
                'published'    => 'Published',
                'draft'        => 'Draft',
            ),
            'display'    => 'Field\Select',
            'nullable'    => false,
            'default'    => 'published'
        ];
    }

    public function featuredField()
    {
        return [
            'name' => 'featured',
            'label' => 'Feature',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No',
            ),
            'display' => 'Field\InputSlider',
            'default' => 'f'
        ];
    }

    public function pinnedField()
    {
        return [
            'name' => 'pinned',
            'label' => 'Pin',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No',
            ),
            'display' => 'Field\InputSlider',
            'default' => 'f'
        ];
    }

    public function imageField()
    {
        return [
            'name' => 'image',
            'label' => 'List View',
            'display' => 'Field\InputFile'
        ];
    }

    public function imageFeaturedField()
    {
        return [
            'name' => 'image_feature',
            'label' => 'Featured View',
            'display' => 'Field\InputFile'
        ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            <div class="top-container">
                {{{ManagerIndexHeader metadata=metadata pagination=pagination}}}
            </div>

           <div class="bottom-container">
              {{#if profiles}}
                    {{{ManagerIndexPagination pagination=pagination}}}
                    {{{ManagerIndexButtons metadata=metadata}}}

                    <table class="ui large table segment manager">
                         <thead>
                               <tr>
                                  <th>Title</th>
                                  <th>Category</th>
                                  <th>Status</th>
                                  <th class="trash">Delete</th>
                               </tr>
                         </thead>
                         <tbody>
                            {{#each profiles}}
                                 <tr data-id="{{dbURI}}">
                                     <td>{{title}}</td>
                                     <td>{{category}}</td>
                                     <td>{{status}}</td>
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

    public function formPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerForm spare=id_spare metadata=metadata}}}
                <div class="top-container">
                    {{{ManagerFormHeader metadata=metadata}}}
                    {{{ManagerFormTabs metadata=metadata}}}
                </div>

                <div class="bottom-container">
                    <div class="ui tab active" data-tab="Main">
                        {{{ManagerFormMainColumn}}}
                        {{{ManagerField . class="left" name="first_name" label="First Name"}}}
                        {{{ManagerField . class="left" name="last_name" label="Last Name"}}}
                            {{{ManagerField . class="left" name="full_name" label="Full Name"}}}
                            {{{ManagerField . class="left" name="title" label="Title"}}}
                            {{{ManagerField . class="left" name="email" label="Email"}}}
                            {{{ManagerField . class="left" name="homepage" label="Homepage"}}}
                            {{{ManagerField . class="left" name="phone" label="Phone"}}}
                            {{{ManagerField . class="left" name="description" label="Summary"}}}
                            {{{id}}}
                            {{{form-token}}}
                        {{{ManagerFormMainColumnClose}}}

                        {{{ManagerFormSideColumn}}}
                            {{{ManagerFormButton modified=modified_date}}}
                            {{{ManagerField . class="fluid" name="status"}}}
                            <div class="ui clearing divider"></div>
                            {{{ManagerField . class="left" name="featured"}}}
                            <br />
                            {{{ManagerField . class="left" name="pinned"}}}
                        {{{ManagerFormSideColumnClose}}}
                    </div>

                    <div class="ui tab" data-tab="Images">
                        {{{ManagerFormMainColumn}}}
                            {{{ManagerField . class="left" name="image" label="List View"}}}
                            {{{ManagerField . class="left" name="image_feature" label="Featured"}}}
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
