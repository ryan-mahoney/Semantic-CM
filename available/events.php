<?php
/*
 * @version .8
 * @link https://raw.github.com/virtuecenter/manager/master/available/events.php
 * @mode upgrade
 *
 * .3 set categories correctly
 * .4 sort removed
 * .5 spelling errors
 * .6 definiton and description for count added
 * .7 more embedded docs
 * .8 missing "Field" suffix on discount code method name
 */
namespace Manager;

class events {
	private $field = false;
    public $collection = 'events';
    public $title = 'Events';
    public $titleField = 'title';
    public $singular = 'Event';
    public $description = '{{count}} events';
    public $definition = 'These content blocks appear on event streams and calendars. They are organized by date and time, typically include an image, short description, and occasionally, a registration option. ';
    public $acl = ['content', 'admin', 'superadmin'];
    public $tabs = ['Main', 'Images', 'Venue', 'Recurring', 'Registration', 'Advanced', 'SEO'];
    public $icon = 'empty calendar';
    public $category = 'Content';
    public $after = 'function';
    public $function = 'ManagerSaved';
    public $storage = [
        'collection' => 'events',
        'key' => '_id'
    ];

	public function excel () {
		return [
			'flatten' => true,
			'title' => 'Events',
			'file' => 'events.xlsx',
			'sort' => ['value.created_date' => 1],
			'cols' => [
				['title'],
				['pinned', 'Pinned?', function ($data) {
					return ($data === false || $data === 'f' ) ? 'No' : 'Yes';
				}],
				['recurring_event', 'Recurring Event?', function ($data) {
					return ($data === false || $data === 'f' ) ? 'No' : 'Yes';
				}],
				['date', 'Date', function (&$data) {
					return date('Y-m-d', $data->sec);
				}],				
				['status'],
				['cost'],
				['categories', 'categories', function (&$data) {
					if (!is_array($data)) {
						return '';
					}
					$categories = '';
					foreach ($data as $value) {
						$categories .= self::$categories[$value] . "\t";
					}
					return str_replace("\t", ', ', trim($categories));
				}],				
			]
		];
	}


	function titleField () {
		return [
			'name' => 'title',
			'label' => 'Title',
			'required' => true,
			'display' => 'InputText'
		];
	}

	function bodyField () {
		return [
			'name' => 'body',
			'label' => 'Body',
			'required' => false,
			'display' => 'Ckeditor'
		];
	}

	function descriptionField () {
		return [
			'name' => 'description',
			'label' => 'Summary',
			'display' => 'Textarea'
		];
	}

	function timeField () {
		return [
			'name' => 'time',
			'label' => 'Time Description',
			'required' => false,
			'display' => 'InputText'
		];
	}

	function costField () {
		return [
			'name' => 'cost',
			'label' => 'Cost Description',
			'required' => false,
			'display' => 'InputText'
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

	function venueField () {
		return [
			'name' => 'venue',
			'label' => 'Venue',
			'required' => false,
			'display' => 'InputText'
		];
	}

	function venue_descriptionField () {
		return [
			'name' => 'venue_description',
			'label' => 'Description',
			'required' => false,
			'display' => 'Textarea'
		];
	}


	function locationField () {
		return [
			'name' => 'location',
			'label' => 'Address',
			'required' => false,
			'display' => 'Textarea'
		];
	}
	
	function contact_infoField () {
		return [
			'name' => 'contact_info',
			'label' => 'Contact Information',
			'required' => false,
			'display' => 'Textarea'
		];
	}

	function urlField () {
		return [
			'name' => 'url',
			'label' => 'URL',
			'required' => false,
			'display' => 'InputText'
		];
	}

	function mapField () {
		return [
			'name' => 'map_url',
			'label' => 'Map URL',
			'required' => false,
			'display' => 'InputText'
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

	function categoriesField () {
		return array(
			'name'		=> 'categories',
			'label'		=> 'Category',
			'required'	=> false,
			'options'	=> function () {
				return $this->db->fetchAllGrouped(
					$this->db->collection('categories')->
						find(['section' => 'Events'])->
						sort(['title' => 1]),
					'_id', 
					'title');
			},
			'display'	=> 'InputToTags',
			'controlled' => true,
			'multiple' => true
		);
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
				return $this->db->distinct('events', 'tags');
			}
		];
	}

/*
	public function image_subField() {
		return array(
			'name' => 'image_sub',
			'label' => 'Venue Images',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> 'vc\ms\site\subdocuments\ImageSimpleSubAdmin'
		);
	}
	
	public function highlight_imagesField() {
		return array(
			'name' => 'highlight_images',
			'label' => 'Highlight Images',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> 'vc\ms\site\subdocuments\ImageSimpleSubAdmin'
		);
	}*/

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

	function dateField() {
		return [
			'name'			=> 'display_date',
			'required'		=> true,
			'display'		=> 'InputDatePicker',
			'transformIn'	=> function ($data) {
				return new \MongoDate(strtotime($data));
			},
			'transformOut'	=> function ($data) {
				return date('m/d/Y', $data->sec);
			},
			'default'		=> function () {
				return date('m/d/Y');
			}
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

    function pinnedField () {
        return [
            'name' => 'pinned',
            'label' => 'Pin',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

    function commentsField () {
        return [
            'name' => 'comments',
            'label' => 'Comments',
            'required' => false,
            'options' => array(
                't' => 'Yes',
                'f' => 'No'
            ),
            'display' => 'InputSlider',
            'default' => 'f'
        ];
    }

    public function events_registrationsField() {
		return [
			'name' => 'events_registrations',
			'label' => 'Registration Options',
			'required' => false,
			'display'	=>	'Manager',
			'manager'	=> 'events_registrations'
		];
	}

	public function events_discountsField() {
		return [
			'name' => 'discount_code',
			'label' => 'Discount Codes',
			'required' => false,
			'display'	=>	'Manager',
			'manager'	=> 'events_discounts'
		];
	}

/*
	function payment_optionsField () {
		return [
			'name' => 'payment_options',
			'label' => 'Payment Options',
			'required' => false,
			'options' => [
				'Credit Card' => 'Credit Card',
				'Check' => 'Check',
				'Cash' => 'Cash'
			],
			'display' => VCPF\Field::selectToPill(),
			'nullable' => true
		];
	}
	
	function ticketsField () {
		return [
			'name' => 'tickets',
			'label' => 'Tickets',
			'required' => false,
			'options' => [
				'Will Call' => 'Will Call',
				'Mail' => 'Mail',
			],
			'display' => VCPF\Field::selectToPill(),
			'nullable' => true
		];
	}

	function default_registrantField () {
		return [
			'name' => 'default_registrant',
			'label' => 'Default Attendee Type',
			'required' => false,
			'options' => [
				'guest' => 'Guest',
				'purchaser' => 'Purchaser'
			],
			'display' => VCPF\Field::select(),
			'default' => 'purchaser'
		];
	}
	
	function venue_urlField () {
		return [
			'name' => 'venue_url',
			'label' => 'Venue URL',
			'required' => false,
			'display' => 'InputText'
		];
	}
	
	function socialsharingField () {
		return [
			'name' => 'sharing_code',
			'label' => 'Social Sharing Code Snippet',
			'required' => false,
			'display' => VCPF\Field::textarea()
		];
	}

	function recurring_eventField () {
		return [
			'name' => 'recurring_event',
			'label' => false,
			'required' => false,
			'options' => [
				't' => 'Yes',
				'f' => 'No'
			],
			'display' => 'InputRadioButton',
			'default' => 'f'
		];
	}

	function end_dateField() {
		return [
			'name'=> 'end_date',
			'label'=> false,
			'required'=>false,
			'display' => VCPF\Field::inputDatePicker(),
			'transformIn' => function ($data) {
				if (empty($data)) {
					return null;
				}
				return new \MongoDate(strtotime($data));
			},
			'transformOut' => function ($data) {
				if (!is_object($data)) {
					$data = new \MongoDate(strtotime('+1 year'));
				}
				return date('m/d/Y', $data->sec);
			},
			'default' => function () {
				return date('m/d/Y');
			}
		];
	}

    function timeStartField () {
        return [
            'name' => 'time_start',
            'label' => 'Start',
            'required' => false,
            'display' => VCPF\Field::select(),
            'options' => [
                "00:00" => 'midnight',
                "00:15" => '12:15 am',
                "00:30" => '12:30 am',
                "00:45" => '12:45 am',
                "01:00" => '01:00 am',
                "01:15" => '01:15 am',
                "01:30" => '01:30 am',
                "01:45" => '01:45 am',
                "02:00" => '02:00 am',
                "02:15" => '02:15 am',
                "02:30" => '02:30 am',
                "02:45" => '02:45 am',
                "03:00" => '03:00 am',
                "03:15" => '03:15 am',
                "03:30" => '03:30 am',
                "03:45" => '03:45 am',
                "04:00" => '04:00 am',
                "04:15" => '04:15 am',
                "04:30" => '04:30 am',
                "04:45" => '04:45 am',
                "05:00" => '05:00 am',
                "05:15" => '05:15 am',
                "05:30" => '05:30 am',
                "05:45" => '05:45 am',
                "06:00" => '06:00 am',
                "06:15" => '06:15 am',
                "06:30" => '06:30 am',
                "06:45" => '06:45 am',
                "07:00" => '07:00 am',
                "07:15" => '07:15 am',
                "07:30" => '07:30 am',
                "07:45" => '07:45 am',
                "08:00" => '08:00 am',
                "08:15" => '08:15 am',
                "08:30" => '08:30 am',
                "08:45" => '08:45 am',
                "09:00" => '09:00 am',
                "09:15" => '09:15 am',
                "09:30" => '09:30 am',
                "09:45" => '09:45 am',
                "10:00" => '10:00 am',
                "10:15" => '10:15 am',
                "10:30" => '10:30 am',
                "10:45" => '10:45 am',
                "11:00" => '11:00 am',
                "11:15" => '11:15 am',
                "11:30" => '11:30 am',
                "11:45" => '11:45 am',
                "12:00" => 'noon',
                "12:15" => '12:15 pm',
                "12:30" => '12:30 pm',
                "12:45" => '12:45 pm',
                "13:00" => '01:00 pm',
                "13:15" => '01:15 pm',
                "13:30" => '01:30 pm',
                "13:45" => '01:45 pm',
                "14:00" => '02:00 pm',
                "14:15" => '02:15 pm',
                "14:30" => '02:30 pm',
                "14:45" => '02:45 pm',
                "15:00" => '03:00 pm',
                "15:15" => '03:15 pm',
                "15:30" => '03:30 pm',
                "15:45" => '03:45 pm',
                "16:00" => '04:00 pm',
                "16:15" => '04:15 pm',
                "16:30" => '04:30 pm',
                "16:45" => '04:45 pm',
                "17:00" => '05:00 pm',
                "17:15" => '05:15 pm',
                "17:30" => '05:30 pm',
                "17:45" => '05:45 pm',
                "18:00" => '06:00 pm',
                "18:15" => '06:15 pm',
                "18:30" => '06:30 pm',
                "18:45" => '06:45 pm',
                "19:00" => '07:00 pm',
                "19:15" => '07:15 pm',
                "19:30" => '07:30 pm',
                "19:45" => '07:45 pm',
                "20:00" => '08:00 pm',
                "20:15" => '08:15 pm',
                "20:30" => '08:30 pm',
                "20:45" => '08:45 pm',
                "21:00" => '09:00 pm',
                "21:15" => '09:15 pm',
                "21:30" => '09:30 pm',
                "21:45" => '09:45 pm',
                "22:00" => '10:00 pm',
                "22:15" => '10:15 pm',
                "22:30" => '10:30 pm',
                "22:45" => '10:45 pm',
                "23:00" => '11:00 pm',
                "23:15" => '11:15 pm',
                "23:30" => '11:30 pm',
                "23:45" => '11:45 pm',
            ],
            'nullable' => true
        ];
    }

    function timeEndField () {
        return [
            'name' => 'time_end',
            'label' => 'End',
            'required' => false,
            'display' => VCPF\Field::select(),
            'options' => [
                "00:00" => 'midnight',
                "00:15" => '12:15 am',
                "00:30" => '12:30 am',
                "00:45" => '12:45 am',
                "01:00" => '01:00 am',
                "01:15" => '01:15 am',
                "01:30" => '01:30 am',
                "01:45" => '01:45 am',
                "02:00" => '02:00 am',
                "02:15" => '02:15 am',
                "02:30" => '02:30 am',
                "02:45" => '02:45 am',
                "03:00" => '03:00 am',
                "03:15" => '03:15 am',
                "03:30" => '03:30 am',
                "03:45" => '03:45 am',
                "04:00" => '04:00 am',
                "04:15" => '04:15 am',
                "04:30" => '04:30 am',
                "04:45" => '04:45 am',
                "05:00" => '05:00 am',
                "05:15" => '05:15 am',
                "05:30" => '05:30 am',
                "05:45" => '05:45 am',
                "06:00" => '06:00 am',
                "06:15" => '06:15 am',
                "06:30" => '06:30 am',
                "06:45" => '06:45 am',
                "07:00" => '07:00 am',
                "07:15" => '07:15 am',
                "07:30" => '07:30 am',
                "07:45" => '07:45 am',
                "08:00" => '08:00 am',
                "08:15" => '08:15 am',
                "08:30" => '08:30 am',
                "08:45" => '08:45 am',
                "09:00" => '09:00 am',
                "09:15" => '09:15 am',
                "09:30" => '09:30 am',
                "09:45" => '09:45 am',
                "10:00" => '10:00 am',
                "10:15" => '10:15 am',
                "10:30" => '10:30 am',
                "10:45" => '10:45 am',
                "11:00" => '11:00 am',
                "11:15" => '11:15 am',
                "11:30" => '11:30 am',
                "11:45" => '11:45 am',
                "12:00" => 'noon',
                "12:15" => '12:15 pm',
                "12:30" => '12:30 pm',
                "12:45" => '12:45 pm',
                "13:00" => '01:00 pm',
                "13:15" => '01:15 pm',
                "13:30" => '01:30 pm',
                "13:45" => '01:45 pm',
                "14:00" => '02:00 pm',
                "14:15" => '02:15 pm',
                "14:30" => '02:30 pm',
                "14:45" => '02:45 pm',
                "15:00" => '03:00 pm',
                "15:15" => '03:15 pm',
                "15:30" => '03:30 pm',
                "15:45" => '03:45 pm',
                "16:00" => '04:00 pm',
                "16:15" => '04:15 pm',
                "16:30" => '04:30 pm',
                "16:45" => '04:45 pm',
                "17:00" => '05:00 pm',
                "17:15" => '05:15 pm',
                "17:30" => '05:30 pm',
                "17:45" => '05:45 pm',
                "18:00" => '06:00 pm',
                "18:15" => '06:15 pm',
                "18:30" => '06:30 pm',
                "18:45" => '06:45 pm',
                "19:00" => '07:00 pm',
                "19:15" => '07:15 pm',
                "19:30" => '07:30 pm',
                "19:45" => '07:45 pm',
                "20:00" => '08:00 pm',
                "20:15" => '08:15 pm',
                "20:30" => '08:30 pm',
                "20:45" => '08:45 pm',
                "21:00" => '09:00 pm',
                "21:15" => '09:15 pm',
                "21:30" => '09:30 pm',
                "21:45" => '09:45 pm',
                "22:00" => '10:00 pm',
                "22:15" => '10:15 pm',
                "22:30" => '10:30 pm',
                "22:45" => '10:45 pm',
                "23:00" => '11:00 pm',
                "23:15" => '11:15 pm',
                "23:30" => '11:30 pm',
                "23:45" => '11:45 pm',
            ],
            'nullable' => true
        ];
    }

	function display_dateField() {
		return [
			'name'=> 'date',
			'label'=> 'Date',
			'required'=>true,
			'display' => VCPF\Field::inputDatePicker(),
			'transformIn' => function ($data) {
				return new \MongoDate(strtotime($data));
			},
			'transformOut' => function ($data) {
				return date('m/d/Y', $data->sec);
			},
			'default' => function () {
				return date('m/d/Y');
			}
		];
	}

	function commentsField () {
		return [
			'name' => 'comments',
			'label' => false,
			'required' => false,
			'options' => [
				't' => 'Yes',
				'f' => 'No'
			],
			'display' => 'InputRadioButton',
			'default' => 'f'
		];
	}

	function registration_quantity_oneField () {
		return [
			'name' => 'registration_quantity_one',
			'label' => 'Quantity Always One?',
			'required' => false,
			'options' => [
				't' => 'Yes',
				'f' => 'No'
			],
			'display' => 'InputRadioButton',
			'default' => 'f'
		];
	}

	function maineventField () {
		return [
			'name' => 'featuredevent',
			'label' => false,
			'required' => false,
			'options' => [
				't' => 'Yes',
				'f' => 'No'
			],
			'display' => 'InputRadioButton',
			'default' => 'f'
		];
	}

	function imageField () {
		return [
			'name' => 'image',
			'label' => 'Image',
			'display' => VCPF\Field::inputFile()
		];
	}
	
	function header_imageField () {
		return [
			'name' => 'header_image',
			'label' => 'Header Image',
			'display' => VCPF\Field::inputFile()
		];
	}
	
	function footer_imageField () {
		return [
			'name' => 'footer_image',
			'label' => 'Footer Image',
			'display' => VCPF\Field::inputFile()
		];
	}
	
	function ticket_imageField () {
		return [
			'name' => 'ticket_image',
			'label' => 'Ticket Background Image ',
			'display' => VCPF\Field::inputFile()
		];
	}

	function featuredImageField () {
		return [
			'name' => 'featured_image',
			'label' => 'Featured Image',
			'display' => VCPF\Field::inputFile()
		];
	}

	function registration_optionsField () {
		return [
			'name' => 'registration_options',
			'label' => 'Registration Options',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> '\vc\ms\site\subdocuments\RegistrationSubAdmin'
		];
	}

	function event_budgetField () {
		return [
			'name' => 'event_budget',
			'label' => 'Event Budget',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> '\vc\ms\site\subdocuments\EventBudgetSubAdmin'
		];
	}

	function itineraryField () {
		return [
			'name' => 'itinerary',
			'label' => 'Itinerary',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> '\vc\ms\site\subdocuments\ItinerarySubAdmin'
		];
	}

	public function speakersField() {
		return array(
			'name' => 'speakers',
			'label' => 'Speakers',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> 'vc\ms\event\subdocument\SpeakersSubAdmin'
		);
	}

	public function registration_formField() {
		return [
			'name' => 'registration_form',
			'label' => 'Registration Form',
			'required' => false,
			'display'	=>	VCPF\Field::select(),
			'options' => function () {
				return VCPF\Config::events()['forms'];
			},
			'nullable' => true
		];
	}

	public function programField() {
		return [
			'name' => 'program',
			'label' => 'Program',
			'required' => false,
			'options'	=> function () {
				return VCPF\Model::db('programs')->
				find()->
				sort(['title' => 1])->
				fetchAllGrouped('_id', 'title');
			},
			'display' => VCPF\Field::select(),
			'nullable' => 'Choose Program',
			'transformIn' => function ($data) {
				return new \MongoId((string)$data);
			}
		];
	}

	function subscriberField () {
		return [
			'name' => 'subscriber',
			'label' => 'Subscribers',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> '\vc\ms\site\subdocuments\SubscriberSubAdmin'
		];
	}

	function recurrence_rulesField () {
		return [
			'name' => 'recurrence_rules',
			'label' => 'Recurrence Rules',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> '\vc\ms\site\subdocuments\RecurrenceRulesSubAdmin'
		];
	}

	function exception_dateField () {
		return [
			'name' => 'exception_date',
			'label' => 'Exception Dates',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> '\vc\ms\site\subdocuments\ExceptionDatesSubAdmin'
		];
	}

	function plus_dateField () {
		return [
			'name' => 'plus_date',
			'label' => 'Included Dates',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> '\vc\ms\site\subdocuments\PlusDatesSubAdmin'
		];
	}
	
	function link_subField () {
		return [
			'name' => 'link_sub',
			'label' => 'Menu',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> '\vc\ms\site\subdocuments\LinkSubAdmin'
		];
	}
	
	function people_subField () {
		return [
			'name' => 'people_sub',
			'label' => 'People',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> '\vc\ms\site\subdocuments\PeopleSubAdmin'
		];
	}
	
	function sponsor_subField () {
		return [
			'name' => 'sponsor_sub',
			'label' => 'Sponsor',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> '\vc\ms\site\subdocuments\SponsorSubAdmin'
		];
	}

	function email_subField () {
		return [
			'name' => 'email_sub',
			'label' => 'Email',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> 'vc\ms\site\subdocuments\EmailMessageSubAdmin'
		];
	}
	
	function discount_codeField () {
		return [
			'name' => 'discount_code',
			'label' => 'Discount Code',
			'required' => false,
			'display'	=>	VCPF\Field::admin(),
			'adminClass'	=> '\vc\ms\site\subdocuments\DiscountCodeSubAdmin'
		];
	}

	function templateField () {
		return [
			'name' => 'fetemplate',
			'label' => 'Front-End Template',
			'required' => false,
            'options' => function () {
                return VCPF\Model::db('lookup_templates')->
                    find(['tags' => 'events'])->
                    sort(array('name' => 1))->
                    fetchAllGrouped('path', 'name');
            },
			'display' => VCPF\Field::select(),
			'nullable' => 'Choose a template'
		];
	}

	function instructorField () {
		return [
			'name' => 'instructor',
			'label' => 'Instructors Featured',
			'required' => false,
			'options' => function () {
				return VCPF\Model::db('instructors')->
					find(array('section' => 'Pages'))->
					sort(array('title' => 1))->
					fetchAllGrouped('_id', 'title');
			},
			'display' => VCPF\Field::select(),
			'nullable' => true
		];
	}

	function categoriesField () {
		return [
			'name' => 'categories',
			'label' => 'Choose a Category',
			'required' => false,
			'options' => function () {
				return VCPF\Model::db('categories')->
					find(['section' => 'Event'])->
					sort(array('title' => 1))->
					fetchAllGrouped('_id', 'title');
				},
			'display' => VCPF\Field::selectToPill()
		];
	}

	function tagsField () {
		return [
			'name' => 'tags',
			'label' => 'Tags',
			'required' => false,
			'transformIn' => function ($data) {
				return VCPF\Regex::csvToArray($data);
			},
			'display' => VCPF\Field::inputToTags(),
			'autocomplete' => function () {
				return VCPF\Model::mongoDistinct('events', 'tags');
			}
		];
	}

	public function documentSaved () {
		return function (&$admin, &$document) {
			$job = new EventFeederJob();
			VCPF\Job::add($job);
		};
	}

	public function documentUpdated () {
		return function ($admin, &$document) {
			$job = new EventFeederJob();
			VCPF\Job::add($job);
		};
	}

	public function documentRemoved () {
		return function ($admin, &$request) {
			$job = new EventFeederJob();
			VCPF\Job::add($job);
		};
	}
	*/
	public function tablePartial () {
        $partial = <<<'HBS'
            <div class="top-container">
                {{#CollectionHeader}}{{/CollectionHeader}}
            </div>

            <div class="bottom-container">
                {{#if events}}
		                {{#CollectionPagination}}{{/CollectionPagination}}
		                {{#CollectionButtons}}{{/CollectionButtons}}
		                
		                <table class="ui large table segment manager sortable">
		                   
                            <col width="40%">
                            <col width="20%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
		                    <thead>
		                        <tr>
		                            <th>Title</th>
		                            <th>Date</th>
		                            <th>Status</th>
		                            <th>Pinned</th>
		                            <th>Recurring</th>
		                            <th class="trash">Delete</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        {{#each events}}
		                            <tr data-id="{{dbURI}}">
		                                
		                                <td>{{title}}</td>
		                                <td></td>
		                                <td>{{#Capitalize}}{{status}}{{/Capitalize}}</td>
		                                <td>{{#BooleanReadable}}{{pinned}}{{/BooleanReadable}}</td>
		                                <td>{{recurring_event}}</td>
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
	                        {{#FieldLeft body Body}}{{/FieldLeft}}
	                        {{#FieldLeft description Summary}}{{/FieldLeft}}
	                        {{#FieldLeft time Time Description}}{{/FieldLeft}}
	                        {{#FieldLeft cost Cost Description}}{{/FieldLeft}}
	                        {{{id}}}
	                    {{/DocumentFormLeft}}                 
	                
	                    {{#DocumentFormRight}}
	                	    {{#DocumentButton}}{{/DocumentButton}}
		                    {{#FieldFull status}}{{/FieldFull}}
		                    <br />
		                    {{#FieldFull display_date}}{{/FieldFull}}
		                    <div class="ui clearing divider"></div>
		                    {{#FieldLeft featured}}{{/FieldLeft}}
		                    <br />
		                    {{#FieldLeft pinned}}{{/FieldLeft}}
		                    <br />
		                    {{#FieldLeft comments}}{{/FieldLeft}}
		                    <div class="ui clearing divider"></div>
		                    {{#FieldFull categories Categories}}{{/FieldFull}}
		                    {{#FieldFull tags Tags}}{{/FieldFull}}
	                    {{/DocumentFormRight}}
	                </div>
	                <div class="ui tab" data-tab="Images">
		                {{#DocumentFormLeft}}
		                    {{#FieldLeft image "List View"}}{{/FieldLeft}}
		                    {{#FieldLeft image_feature Featured}}{{/FieldLeft}}
		                    {{#FieldLeft image_sub}}{{/FieldLeft}}
		                    {{#FieldLeft highlight_images}}{{/FieldLeft}}

		                {{/DocumentFormLeft}}                 
		                
		                {{#DocumentFormRight}}
			                {{#DocumentButton}}{{/DocumentButton}}
		                {{/DocumentFormRight}}
		            </div>
		              <div class="ui tab" data-tab="Venue">
		                {{#DocumentFormLeft}}
		                    {{#FieldLeft venue Venue}}{{/FieldLeft}}
		                    {{#FieldLeft venue_description Description}}{{/FieldLeft}}
		                    {{#FieldLeft location Address}}{{/FieldLeft}}
		                    {{#FieldLeft contact_info Contact Information}}{{/FieldLeft}}
		                    {{#FieldLeft url URL}}{{/FieldLeft}}
		                    {{#FieldLeft map_url Map URL}}{{/FieldLeft}}

		                {{/DocumentFormLeft}}                 
		                
		                {{#DocumentFormRight}}
			                {{#DocumentButton}}{{/DocumentButton}}
		                {{/DocumentFormRight}}
		            </div>
		            <div class="ui tab" data-tab="Registration">
		                {{#DocumentFormLeft}}
		                    {{#FieldEmbedded field="events_registrations" manager="events_registrations"}}
		                    {{#FieldEmbedded field="discount_code" manager="events_discounts"}}
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