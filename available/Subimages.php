<?php
/*
 * @version 2
 * @link https://raw.github.com/Opine-Org/Semantic-CM/master/available/Subimages.php
 * @mode upgrade
 *
 * .3 bad field label
 * .4 delete feature
 * .5 missing image field
 * .6 definition and description for count added
 * .7 name attributes
 */
namespace Manager;

class Subimages {
    public $collection = 'Collection\Subimages';
    public $title = 'Subimage';
    public $titleField = 'title';
    public $singular = 'Image';
    public $description = '{{count}} subimages';
    public $definition = 'Coming Soon';
    public $acl = ['content', 'admin', 'superadmin'];
    public $icon = 'browser';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'embeddedUpsert';
    public $notice = 'Subimages';
    public $embedded = true;

    function captionField () {
        return [
            'name'        => 'caption',
            'required'    => false,
            'display'    => 'Field\InputText'
        ];
    }

    function copyrightField () {
        return [
            'name'        => 'copyright',
            'required'    => false,
            'display'    => 'Field\InputText'
        ];
    }

    function titleField () {
        return [
            'name'        => 'title',
            'required'    => false,
            'display'    => 'Field\InputText'
        ];
    }

    function imageField () {
        return [
            'name' => 'file',
            'display' => 'Field\InputFile'
        ];
    }

    public function indexPartial () {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedIndexHeader label="Images"}}}
            {{#if image_individual}}
                <table class="ui table manager segment manager sortable">
                    <col width="10%">
                    <col width="40%">
                    <col width="40%">
                    <col width="10%">
                    <thead>
                        <tr>
                            <th><i class="shuffle basic icon"></i></th>
                            <th>Image</th>
                            <th>Caption</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each image_individual}}
                            <tr data-id="{{dbURI}}">
                                <td class="handle"><i class="reorder icon"></i></td>
                                <td>{{{ImageResize file}}}</td>
                                <td>{{caption}}</td>
                                <td><div class="manager trash ui icon button"><i class="trash icon small"></i></div></td>
                            </tr>
                        {{/each}}
                    </tbody>
                </table>
            {{else}}
                {{{ManagerEmbeddedIndexEmpty singular="image"}}}
            {{/if}}
HBS;
        return $partial;
    }

    public function formPartial () {
        $partial = <<<'HBS'
            {{{ManagerEmbeddedFormHeader metadata=metadata}}}                
                {{{ManagerField . class="fluid" name="file" label="Image"}}}
                {{{ManagerField . class="fluid" name="caption" label="Caption"}}}
                {{{id}}}
                {{{form-token}}}
            {{{ManagerEmbeddedFormFooter}}}    
HBS;
        return $partial;
    }
}