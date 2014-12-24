<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Subcarousels.php
 * @mode upgrade
 *
 * .3 field name isssues
 * .4 typo
 * .5 missing caption field
 * .6 change save function
 * .7 bad field name
 * .8 wrong collection
 * .9 mark as embedded
 * 1.0 delete feature
 * 1.1 definition and description for count added
 * 1.2 name attributes
 */
namespace Manager;

class Subcarousels
{
    public $collection = 'Collection\Carousels';
    public $title = 'SubCarousels';
    public $titleField = 'title';
    public $singular = 'SubCarousel';
    public $description = '{{count}} subcarousels';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $embedded = true;
    public $function = 'embeddedUpsert';
    public $notice = 'Carousel Saved';

    public function imageField()
    {
        return [
            'name'          => 'file',
            'label'         => 'Image',
            'display'       => 'Field\InputFile'
        ];
    }

    public function urlField()
    {
        return [
            'name'          => 'url',
            'label'         => 'URL',
            'required'      => false,
            'display'       => 'Field\InputText'
        ];
    }

    public function targetField()
    {
        return [
            'name'          => 'target',
            'label'         => 'Redirect',
            'required'      => true,
            'options'       => [
                '_self'         => 'Self',
                '_blank'        => 'Blank',
                '_top'          => 'Top',
                '_parent'       => 'Parent',
            ],
            'display'       => 'Field\Select',
            'nullable'      => false,
            'default'       => 'self'
        ];
    }

    public function captionField()
    {
        return [
            'name'          => 'caption',
            'label'         => 'Caption',
            'required'      => false,
            'display'       => 'Field\InputText'
        ];
    }

    public function indexPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedIndexHeader label="Images"}}}
            {{#if carousel_individual}}
                <table class="ui table manager segment">
                    <thead>
                        <tr>
                            <th>Caption</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each carousel_individual}}
                            <tr data-id="{{dbURI}}">
                                <td>{{caption}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{{ManagerEmbeddedIndexEmpty singular="carousel_individual"}}}
            {{/if}}
HBS;

        return $partial;
    }

    public function formPartial()
    {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedFormHeader metadata=metadata}}}
                {{{ManagerField . class="fluid" name="file" label="Image"}}}
                {{{ManagerField . class="fluid" name="url" label="URL"}}}
                {{{ManagerField . class="fluid" name="target" label="Target"}}}
                {{{ManagerField . class="fluid" name="caption" label="Caption"}}}
                {{{id}}}
                {{{form-token}}}
            {{{ManagerEmbeddedFormFooter}}}
HBS;

        return $partial;
    }
}
