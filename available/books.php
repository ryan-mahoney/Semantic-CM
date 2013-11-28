<?php
/*
 * @version .2
 * @link https://raw.github.com/virtuecenter/manager/master/available/books.php
 * @mode upgrade
 */
namespace Manager;

class books {
	private $field = false;
    public $collection = 'books';
    public $form = 'books';
    public $title = 'Books';
    public $singular = 'Book';
    public $description = '4 books';
    public $definition = '....';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images', 'SEO'];
    public $icon = 'book';
    public $category = 'Content';
    public $notice = 'Book Saved';
    public $storage = [
        'collection' => 'books',
        'key' => '_id'
    ];

    function titleField () {
		return [
			'name'		=> 'title',
			'label'		=> 'Title',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function descriptionField () {
		return [
			'name' => 'description',
			'label' => 'Body',
			'display' => 'Ckeditor'
		];
	}

	function short_descriptionField () {
		return [
			'name' => 'short_description',
			'label' => 'Summary',
		    'display' => 'Textarea'
		];
	}

	function imageField () {
		return [
			'name' => 'image',
			'label' => 'Book Cover Image',
			'display' => 'InputFile'
		];
	}

	function imageListField () {
		return [
			'name' => 'image',
			'label' => 'List View',
			'display' => 'InputFile'
		];
	}

	function imageFeaturedField () {
		return [
			'name' => 'image_feature',
			'label' => 'Featured View',
			'display' => 'InputFile'
		];
	}

	function linkField () {
		return [
			'name'		=> 'link',
			'label'		=> 'URL',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}


	function priceField () {
		return [
			'name'		=> 'price',
			'label'		=> 'Price',
			'required'	=> true,
			'display'	=> 'InputText'
		];
	}

	function statusField () {
		return [
			'name'		=> 'status',
			'required'	=> true,
			'options'	=> array(
				'published'	=> 'Published',
				'draft'		=> 'Draft'
			),
			'display'	=> 'Select',
			'nullable'	=> false,
			'default'	=> 'published'
		];
	}

	function featuredField () {
        return [
            'name' => 'featured',
            'label' => 'Feature',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }



    function code_nameField () {
		return [
			'name' => 'code_name',
			'display'	=> 'InputText'
		];
	}

	function metakeywordsField () {
		return [
			'name' => 'metadata_keywords',
			'display'	=> 'InputText'
		];
	}

	function metadescriptionField () {
		return [
			'name' => 'metadata_description',
			'display'	=> 'InputText'
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
				return $this->db->distinct('books', 'tags');
			}
		];
	}

	function categoriesField () {
		return array(
			'name'		=> 'categories',
			'label'		=> 'Category',
			'required'	=> false,
			'options'	=> function () {
				return $this->db->fetchAllGrouped(
					$this->db->collection('categories')->
						find(['section' => 'Books'])->
						sort(['title' => 1]),
					'_id', 
					'title');
			},
			'display'	=> 'InputToTags',
			'controlled' => true,
			'multiple' => true
		);
	}

	 public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#CollectionPagination}}{{/CollectionPagination}}
                {{#CollectionButtons}}{{/CollectionButtons}}
                
                <table class="ui large table segment manager">
                    <col width="20%">
                    <col width="70%">
                    <col width="10%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Feature</th>
                            <th class="trash">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#each books}}
                            <tr data-id="{{dbURI}}">
                                <td>{{title}}</td>
                                <td>{{status}}</td>
                                <td>{{featured}}</td>
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
		                    {{#FieldLeft description Body}}{{/FieldLeft}}
		                    {{#FieldLeft short_description Summary}}{{/FieldLeft}}
		                    {{#FieldLeft link URL}}{{/FieldLeft}}
		                    {{#FieldLeft price Price}}{{/FieldLeft}}
		                    {{{id}}}
		                {{/DocumentFormLeft}}                 
		                
		                {{#DocumentFormRight}}
			                {{#DocumentButton}}{{/DocumentButton}}
			                {{#FieldFull status}}{{/FieldFull}}
			                <br>
		       	        	{{#FieldLeft featured}}{{/FieldLeft}}
		       	        	<div class="ui clearing divider"></div>
		       	        	{{#FieldFull categories Categories}}{{/FieldFull}}
		       	        	{{#FieldFull tags Tags}}{{/FieldFull}}
		                {{/DocumentFormRight}}
		            </div>
		            <div class="ui tab" data-tab="External Article">
		                {{#DocumentFormLeft}}
		                    {{#FieldLeft }}{{/FieldLeft}}
		                    {{#FieldLeft }}{{/FieldLeft}}
		                    {{#FieldLeft }}{{/FieldLeft}}
		                    {{#FieldLeft }}{{/FieldLeft}}
		                {{/DocumentFormLeft}}                 
		                
		                {{#DocumentFormRight}}
			                {{#DocumentButton}}{{/DocumentButton}}
		                {{/DocumentFormRight}}
		            </div>
		            <div class="ui tab" data-tab="Images">
		                {{#DocumentFormLeft}}
		                    {{#FieldLeft image Book Cover Image}}{{/FieldLeft}}
		                    {{#FieldLeft image List View Image}}{{/FieldLeft}}
		                    {{#FieldLeft image_feature Featured View Image}}{{/FieldLeft}}
		                {{/DocumentFormLeft}}                 
		                
		                {{#DocumentFormRight}}
			                {{#DocumentButton}}{{/DocumentButton}}
		                {{/DocumentFormRight}}
		            </div>
		            <div class="ui tab" data-tab="SEO">
		                {{#DocumentFormLeft}}
		                    {{#FieldLeft code_name Slug}}{{/FieldLeft}}
		                    {{#FieldLeft metadata_description Description}}{{/FieldLeft}}
		              		{{#FieldLeft metadata_keywords Keywords}}{{/FieldLeft}}
		                {{/DocumentFormLeft}}
		                
		                {{#DocumentFormRight}}
			                {{#DocumentButton}}{{/DocumentButton}}
		                {{/DocumentFormRight}}
		            </div>
	            </div>
	        </form>
HBS;
        return $partial;
    }
}
	