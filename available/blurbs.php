<?php
/*
 * @version .4
 * @link https://raw.github.com/virtuecenter/manager/master/available/blurbs.php
 * @mode upgrade
 */
namespace Manager;

class blurbs {
	private $field = false;
	public $collection = 'blurbs';
	public $title = 'Blurbs';
	public $titleField = 'title';
	public $singular = 'Blurb';
	public $description = '5 blurbs';
	public $definition = 'Blurbs are used to control small blocks of text on a website.';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'basic content';
	public $category = 'Content';
	public $after = 'function';
    public $function = 'ManagerSaved';
	public $storage = [
		'collection' => 'blurbs',
		'key' => '_id'
	];

	public function __construct ($field=false) {
		$this->field = $field;
	}
	
	function titleField () {
		return [
			'name' => 'title',
			'placeholder' => 'Title',
			'required' => true,
			'display' => 'InputText'
		];
	}

	function bodyField () {
		return [
			'name' => 'body',
			'required' => false,
			'display' => 'Ckeditor'		
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
			'options' => function () {
				return $this->db->distinct('blurbs', 'tags');
			}
		];
	}

	public function tablePartial () {
		$partial = <<<'HBS'
			<div class="top-container">
				{{#CollectionHeader}}{{/CollectionHeader}}
			</div>

			<div class="bottom-container">
				{{#if blurbs}}
					{{#CollectionPagination}}{{/CollectionPagination}}
					{{#CollectionButtons}}{{/CollectionButtons}}
					
					<table class="ui large table segment manager sortable">
					        <col width="10%">
                            <col width="40%">
                            <col width="40%">
                            <col width="10%">
				  		<thead>
							<tr>
								<th><i class="shuffle basic icon"></i></th>
								<th>Title</th>
								<th>Tags</th>
								<th class="trash">Delete</th>
							</tr>
				  		</thead>
				   		<tbody>
				   			{{#each blurbs}}
								<tr data-id="{{dbURI}}">
								    <td class="handle"><i class="reorder icon"></i></td>
									<td>{{title}}</td>
									<td></td>
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
			        <div class="ui tab active" data-tab="Main">
				        {{#DocumentFormLeft}}
				    	    {{#FieldLeft title Title required}}{{/FieldLeft}}
				    	    {{#FieldLeft body required}}{{/FieldLeft}}
				    	    {{{id}}}
				        {{/DocumentFormLeft}}
					    {{#DocumentFormRight}}
						    {{#DocumentButton}}{{/DocumentButton}}
						    {{#FieldFull tags Tags}}{{/FieldFull}}
					    {{/DocumentFormRight}}
					</div>
				</div>
			</form>
HBS;
		return $partial;
	}
}