<?php
namespace Manager;

class blurbs {
	public $collection = 'blurbs';
	public $form = 'blurbs';
	public $title = 'Blurbs';
	public $single = 'Blurb';
	public $decription = '5 blurbs';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'basic content';
	public $category = 'Content';

	public function tablePartial () {
		$partial = <<<'HBS'
			<br />
			<h1 class="ui manager header">Blurbs</h1>

<div class="ui borderless pagination menu fluid">
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
        <div class="ui teal button">Add Blurb</div>
    </div>
</div>

			<table class="ui table segment">
				<thead>
					<tr><th>Name</th></tr>
				</thead>
					<tbody>
					{{#each blurbs}}
						<tr><td>{{title}}</td></tr>
					{{/each}}
				</tbody>
			</table>
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			<h2 class="ui header">Add Blurb</h2>
    		<form class="ui form segment" data-xhr="true" method="post" action="/Manager/form/blurbs">
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