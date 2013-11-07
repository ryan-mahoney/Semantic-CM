<?php
/*
 * @version .2
 * @link https://raw.github.com/virtuecenter/manager/master/available/blurbs.php
 * @mode upgrade
 */
namespace Manager;

class blurbs {
	private $field = false;
	public $collection = 'blurbs';
	public $form = 'blurbs';
	public $title = 'Blurbs';
	public $single = 'Blurb';
	public $description = '5 blurbs';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'basic content';
	public $category = 'Content';
	public $notice = 'Blurb Saved';
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

/*
	function tagsField () {
		return [
			'name' => 'tags',
			'label' => 'Tags',
			'required' => false,
			'transformIn' => function ($data) {
				return $this->field->csvToArray($data);
			},
			'display' => $this->field->inputToTags(),
			'autocomplete' => function () {
				return $this->db->mongoDistinct('blurb', 'tags');
			},
		];
	}
*/

	public function tablePartial () {
		$partial = <<<'HBS'
			<br />
			<h1 class="ui manager header">Blurbs</h1>

<div class="ui borderless pagination menu large fluid">
  <a class="item">
    <i class="icon left arrow"></i> Previous
  </a>
  <a class="item">1</a>
  <a class="item">2</a>
  <a class="item">3</a>
  <a class="item">4</a>
  <a class="item">5</a>
  <a class="item">6</a>
  <a class="item">
    Next <i class="icon right arrow"></i>
  </a>
  <div class="item right">
        <div class="ui teal button large manager add">Add</div>
    </div>
</div>

			<table class="ui table manager segment padded">
				<thead>
					<tr><th>Name</th></tr>
				</thead>
					<tbody>
					{{#each blurbs}}
						<tr data-id="{{_id}}">
							<td>{{title}}</td>
						</tr>
					{{/each}}
				</tbody>
			</table>
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			<h2 class="ui manager header">Blurb</h2>
    		<form class="ui form segment" data-xhr="true" method="post" action="/Manager/manager/blurbs">
			    <div class="ui warning message">
			        <div class="header">There was a problem</div>
			        <ul class="list">
			        </ul>
			    </div>
			    <div class="field" style="width: 96%; margin-left: 2%">
			        <label>Title</label>
			        <div class="ui left labeled input">
			            {{{title}}}
			            <div class="ui corner label">
			            	<i class="icon asterisk"></i>
			            </div>
			        </div>
			    </div>

			    <div class="field" style="width: 96%; margin-left: 2%">
			        <label>Body</label>
			        <div class="ui left labeled input">
			            {{{body}}}
			            <div class="ui corner label">
			                <i class="icon asterisk"></i>
			            </div>
			        </div>
			    </div>
			    {{{id}}}
			    <input type="submit" class="fluid ui blue submit button" value="Save" style="margin-top: 20px; margin-left: 2%; width: 96%" />
			</form>
HBS;
		return $partial;
	}
}