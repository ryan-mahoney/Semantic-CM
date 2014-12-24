<?php
/*
 * @version 2
 * @link https://raw.github.com/virtuecenter/manager/master/available/EventsImages.php
 * @mode upgrade
 *
 */
namespace Manager;

class EventsImages
{
    public $collection = 'Collection\Events';
    public $title = 'Venue Images';
    public $titleField = 'title';
    public $singular = 'Venue Image';
    public $description = '{{count}} images';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $embedded = true;

    public function imageField()
    {
        return [
            'name' => 'image',
            'label' => 'Image',
            'display' => 'Field\InputFile'
        ];
    }

    public function titleField()
    {
        return [
            'name'        => 'heading',
            'label'        => 'Heading',
            'required'    => true,
            'display'    => 'Field\InputText'
        ];
    }

    public function descriptionField()
    {
        return [
            'name' => 'description',
            'label' => 'Description',
            'display' => 'Field\Textarea'
        ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
			{{{ManagerEmbeddedIndexHeader label="Venue Images"}}}
			{{#if image_sub}}
				<table class="ui table manager segment">
					<thead>
						<tr>
							<th>Image</th>
							<th>Title</th>
							<th class="trash">Delete</th>
						</tr>
					</thead>
					<tbody>
						{{#each image_sub}}
							<tr data-id="{{dbURI}}">
							    <td>{{{ImageResize image}}}</td>
								<td>{{heading}}</td>
								<td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
							</tr>
						{{/each}}
					</tbody>
				</table>
		    {{else}}
			    {{{ManagerEmbeddedIndexEmpty singular="Venue Image"}}}
	        {{/if}}
HBS;

        return $partial;
    }

    public function formPartial()
    {
        $partial = <<<'HBS'
			{{{ManagerEmbeddedFormHeader metadata=metadata}}}
		        {{{ManagerField . class="fluid" name="image" label="Image"}}}
			    {{{ManagerField . class="fluid" name="heading" label="Heading"}}}
			    {{{ManagerField . class="fluid" name="description" label="Description"}}}
			    {{{id}}}
				{{{form-token}}}
			{{{ManagerEmbeddedFormFooter}}}
			<div style="padding-bottom:100px"></div>
HBS;

        return $partial;
    }
}
