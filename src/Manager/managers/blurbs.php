<?php
namespace Manager;

class blurbs {
	public $collection = 'blurbs';
	public $form = 'blurbs';
	public $titleTable = 'Blurbs';
	public $titleCard = 'Blurbs';
	public $decriptionCard = '5 blurbs';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'basic content';
	public $category = 'Content';

	public function row (&$document) {
		return [
			'title' => $document['title']
		];
	}

	public function partial () {
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