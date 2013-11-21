<?php
/*
 * @version .2
 * @link https://raw.github.com/virtuecenter/manager/master/available/menus.php
 * @mode upgrade
 */
namespace Manager;

class menus {
	private $field = false;
	public $collection = 'menus';
	public $form = 'menus';
	public $title = 'Menus';
	public $single = 'Menu';
	public $description = '4 menu items';
	public $definition = 'Menus are used for the navigation of your public website.';
	public $acl = ['content', 'admin', 'superadmin'];
	public $icon = 'browser';
	public $category = 'Content';
	public $notice = 'Menu Saved';
	public $storage = [
		'collection' => 'menus',
		'key' => '_id'
	];

	public function __construct ($field=false) {
		$this->field = $field;
	}

	function labelField () {
		return [
			'name'		=> 'label',
			'placeholder'		=> 'Label',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}	

	function urlField () {
		return [
			'name'		=> 'url',
			'placeholder'		=> 'URL',
			'required'	=> false,
			'display'	=> 'InputText'
		];
	}

/*
	function imageField () {
		return [
			'name' => 'file',
			'placeholder' => 'Image',
			'display' => VCPF\Field::inputFile()
		];
	}
*/

	public function linkField() {
		return [
			'name' => 'link',
			'required' => false,
			'display'	=>	'Manager',
			'manager'	=> 'menu_links'
		];
	}

	public function tablePartial () {
		$partial = <<<'HBS'
			{{#CollectionHeader}}{{/CollectionHeader}}

			<div class="bottom-container">
				{{#CollectionPagination}}{{/CollectionPagination}}
				{{#CollectionButtons}}{{/CollectionButtons}}
				
				<table class="ui large table segment manager">
			  		<thead>
						<tr>
							<th>Title</th>
							<th class="trash">Delete</th>
						</tr>
			  		</thead>
			   		<tbody>
			   			{{#each menus}}
							<tr data-id="{{dbURI}}">
								<td>{{label}}</td>
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
				{{#CollectionButtons}}{{/CollectionButtons}}
			</div>
HBS;
		return $partial;
	}

	public function formPartial () {
		$partial = <<<'HBS'
			<div class="top-container">
				   <div class="ui huge breadcrumb container">
					   <a class="section" href="/"><h2>Dashboard</h2></a>
					     <i class="right arrow icon divider"></i>
					   <a class="section" href="/?content"><h2>Content</h2></a>
					     <i class="right arrow icon divider"></i>
					   <a class="section" href="/Manager/list/menus"><h2>Menus</h2></a>
					    <i class="right arrow icon divider"></i>
					   <a class="active section"><h2>Menu</h2></a>
			      </div>
				   <div class="ui ignored divider container padding"></div>
					  <div class="ui two column grid container padding">
						  <div class="column fontSize">
					          <p>A blog is a discussion or informational site published on the World Wide Web.</p>
					      </div>

					       <div id="right" class="column fontSize">
					      <p>1-40 of 50 Total</p>
					      </div>
		              </div>
		             
		          <div class="ui top attached tabular menu container">
		                <a class="active item bg-image align-left">Main</a>
		                <a class="item bg-image">Views</a>
		                <a class="item bg-image">External Article</a>
		               <div class="right menu align-right">
		                       <div class="ui teal medium buttons">
		                           <div class="ui button manager submit">Save</div>
		                               <div class="ui floating dropdown icon button top right pointing">
		                                   <i class="dropdown icon"></i>
		                                   <div class="menu">
		                                   <div class="item" data-value="horizontal flip">Horizontal Flip</div>
		                                   <div class="item" data-value="fade up">Fade Up</div>
		                                   <div class="item" data-value="scale">Scale</div>
		                               </div>
		                           </div>
		                       </div>
		               </div>
		          </div>
			  </div>





			<form data-xhr="true" method="post" action="/Manager/manager/menus">
				<div class="bottom-container">
				    <div class="ui divided grid">
			             <div class="row">
			             	<div class="ten wide column manager main">
			             		<div class="ui form">
			                        <div class="field"required>
			                           	<label>Label</label>
										<div class="ui left labeled input">
											{{{label}}}
											<div class="ui corner label">
												<i class="icon asterisk"></i>
											</div>
										</div>
			                        </div>
			                    </div>
			                    <div class="ui form">
			                        <div class="field">
			                            <label>URL</label>
										<div class="ui left labeled input">
											{{{url}}}
											<div class="ui corner label">
												<i class="icon asterisk"></i>
											</div>
										</div>
			                        </div>
			                    </div>

			                    <div class="field embedded" data-field="link" data-manager="menu_links" style="width: 96%; margin-left: 2%">
									{{{link}}}
								</div>
								{{{id}}}
			             	</div>



			             	 <div class="two wide column manager sidebar">
			             	 	<div class="ui form">
			           	            <h3>Status</h3>
			                       <div class="grouped inline fields">
			                          <div class="field">
			                              <div class="ui radio checkbox">
			                                  <input type="radio" name="status" checked="checked">
			                                  <label>Draft</label>
			                              </div>
			                          </div>
			                          <div class="field">
			                              <div class="ui radio checkbox">
			                                  <input type="radio" name="status">
			                                  <label>Published</label>
			                              </div>
			                          </div>
			                       </div>
			                   </div>
			                      <div class="ui form">
			                          <div class="date field">
			                              <label></label>
			                              <input type="text" placeholder="xx/xx/xxxx">
			                          </div>
			                     </div>

			                     	<div class="ui toggle checkbox">
			                            <input type="checkbox" name="feature">
			                            <label>Feature</label>
			                       </div><br>
			                       <div class="ui toggle checkbox">
			                            <input type="checkbox" name="pin">
			                            <label>Pin</label>
			                       </div><br>
			                       <div class="ui toggle checkbox">
			                            <input type="checkbox" name="comment">
			                            <label>Comment</label>
			                       </div><br>
			                        <div class="ui selection dropdown margin">
			                            <div class="text">Author</div>
			                             <i class="dropdown icon"></i>
			                                  <div class="menu">
			                                      <div class="item">First</div>
			                                      <div class="item">Second</div>
			                                      <div class="item">Third</div>
			                                 </div>
			                        </div>
			                         <div class="ui selection dropdown margin">
			                            <div class="text">Category</div>
			                             <i class="dropdown icon"></i>
			                                  <div class="menu">
			                                      <div class="item">First</div>
			                                      <div class="item">Second</div>
			                                      <div class="item">Third</div>
			                                 </div>
			                        </div>
			                        <div class="ui form margin">
			                            <div class="inline field">
			                                <label></label>
			                                   <input type="text" placeholder="tags">
			                            </div>
			                        </div>
			             	</div>
			             </div>
			        </div>
			    </div>
			</form>
HBS;
		return $partial;
	}
}