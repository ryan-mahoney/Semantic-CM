<?php
function assetsRead($folder)
{
    $dir = new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($dir);
    $fileList = [];
    foreach ($files as $file) {
        $fileList[] = $file->getPathname();
    }

    return $fileList;
}

function array_delete($array, $element)
{
    return array_diff($array, [$element]);
}

$strings = [
    '{{#ArrayToCSV}}{{tags}}{{/ArrayToCSV}}' => '{{{ArrayToCSV tags}}}',
    '{{#Capitalize}}{{status}}{{/Capitalize}}' => '{{{Capitalize status}}}',
    '{{#CategoriesCSV}}{{categories}}{{/CategoriesCSV}}' => '{{{CategoriesCSV categories}}}',
    '{{#BooleanReadable}}{{featured}}{{/BooleanReadable}}' => '{{{BooleanReadable featured}}}',
    '{{#BooleanReadable}}{{pinned}}{{/BooleanReadable}}' => '{{{BooleanReadable pinned}}}',
    '{{#CollectionButtons}}{{/CollectionButtons}}' => '{{{ManagerIndexButtons metadata=metadata}}}',
    '{{#CollectionEmpty}}{{/CollectionEmpty}}' => '{{{ManagerIndexBlankSlate metadata=metadata}}}',
    '{{#CollectionHeader}}{{/CollectionHeader}}' => '{{{ManagerIndexHeader metadata=metadata pagination=pagination}}}',
    '{{#CollectionPagination}}{{/CollectionPagination}}' => '{{{ManagerIndexPagination pagination=pagination}}}',
    '{{#DocumentButton}}{{/DocumentButton}}' => '{{{ManagerFormButton modified=modified_date}}}',
    '{{#DocumentButton}}' => '{{{ManagerFormButton modified=modified_date}}}',
    '{{/DocumentFormLeft}}' => '{{{ManagerFormMainColumnClose}}}',
    '{{#DocumentFormLeft}}' => '{{{ManagerFormMainColumn}}}',
    '{{/DocumentFormRight}}' => '{{{ManagerFormSideColumnClose}}}',
    '{{#DocumentFormRight}}' => '{{{ManagerFormSideColumn}}}',
    '{{#DocumentHeader}}{{/DocumentHeader}}' => '{{{ManagerFormHeader metadata=metadata}}}',
    '{{#DocumentHeader}}' => '{{{ManagerFormHeader metadata=metadata}}}',
    '{{#DocumentTabs}}{{/DocumentTabs}}' => '{{{ManagerFormTabs metadata=metadata}}}',
    '{{#DocumentTabs}}' => '{{{ManagerFormTabs metadata=metadata}}}',
    '{{#EmbeddedCollectionEmpty' => '{{{ManagerEmbeddedIndexEmpty',
    '{{#EmbeddedCollectionHeader' => '{{{ManagerEmbeddedIndexHeader',
    '{{#EmbeddedFooter}}{{/EmbeddedFooter}}' => '{{{ManagerEmbeddedFormFooter}}}',
    '{{#EmbeddedHeader}}{{/EmbeddedHeader}}' => '{{{ManagerEmbeddedFormHeader metadata=metadata}}}',
    '{{#EmbeddedFooter}}' => '{{{ManagerEmbeddedFormFooter}}}',
    '{{#EmbeddedHeader}}' => '{{{ManagerEmbeddedFormHeader metadata=metadata}}}',
    '{{#FieldEmbedded field="image_sub" manager="events_images"}}' => '{{{ManagerFieldEmbedded field="image_sub" manager="EventsImages"}}}',
    '{{#FieldEmbedded department_profiles department_profiles}}{{/FieldEmbedded}}' => '{{{ManagerFieldEmbedded field="department_profiles" manager="DepartmentProfiles" label="Profiles"}}}',
    '{{#FieldEmbedded field="carousel_individual" manager="subcarousels" Frames}}' => '{{{ManagerFieldEmbedded field="carousel_individual" manager="Subcarousels" label="Frames"}}}',
    '{{#FieldEmbedded field="discount_code" manager="events_discounts"}}' => '{{{ManagerFieldEmbedded field="discount_code" manager="EventsDiscounts" label="Discount Codes"}}}',
    '{{#FieldEmbedded field="email_sub" manager="events_emails"}}' => '{{{ManagerFieldEmbedded field="email_sub" manager="EventsEmails" label="Email Messages"}}}',
    '{{#FieldEmbedded field="exception_date" manager="events_exceptions"}}' => '{{{ManagerFieldEmbedded field="exception_date" manager="EventsExceptions" label="Exception Dates"}}}',
    '{{#FieldEmbedded field="highlight_images" manager="events_highlights"}}' => '{{{ManagerFieldEmbedded field="highlight_images" manager="EventsHighlights" label="Highlight Images"}}}',
    '{{#FieldEmbedded field="image_individual" manager="subimages" Images}}' => '{{{ManagerFieldEmbedded field="image_individual" manager="Subimages" label="Images"}}}',
    '{{#FieldEmbedded field="link" manager="menu_links"}}' => '{{{ManagerFieldEmbedded field="link" manager="MenuLiks" label="Links"}}}',
    '{{#FieldEmbedded field="link_sub" manager="events_links"}}' => '{{{ManagerFieldEmbedded field="link_sub" manager="EventsLinks" label="Links"}}}',
    '{{#FieldEmbedded field="people_sub" manager="events_peoples"}}' => '{{{ManagerFieldEmbedded field="people_sub" manager="EventsPeoples" label="People"}}}',
    '{{#FieldEmbedded field="plus_date" manager="events_plus"}}' => '{{{ManagerFieldEmbedded field="plus_date" manager="events_plus" label="Added Dates"}}}',
    '{{#FieldEmbedded field="recurrence_rules" manager="events_recurrences"}}' => '{{{ManagerFieldEmbedded field="recurrence_rules" manager="EventsRecurrences" label="Recurring Rules"}}}',
    '{{#FieldEmbedded field="registration_options" manager="events_registrations"}}' => '{{{ManagerFieldEmbedded field="registration_options" manager="EventsRegistrations" label="Registration Options"}}}',
    '{{#FieldEmbedded field="sponsor_sub" manager="events_sponsors"}}' => '{{{ManagerFieldEmbedded field="sponsor_sub" manager="EventsSponsors" label="Sponsors"}}}',
    '{{#FieldFull' => '{{{ManagerField . class="fluid" name="',
    'required}}{{/FieldFull}}' => 'required="true"}}}',
    '}}{{/FieldFull}}' => '}}}',
    '{{#FieldLeft ' => '{{{ManagerField . class="left" name="',
    'required}}{{/FieldLeft}}' => 'required="true"}}}',
    '}}{{/FieldLeft}}' => '}}}',
    '{{#Form}}{{/Form}}' => '{{{ManagerForm spare=id_spare metadata=metadata}}}',
    '{{#ImageResize}}{{image}}{{/ImageResize}}' => '{{{ImageResize image}}}',
    '{{#MongoDate field="display_date"}}' => '{{{MongoDate display_date}}}',
    '{{#ImageResize}}{{file}}{{/ImageResize}}' => '{{{ImageResize file}}}',
    '{{#Capitalize}}{{type}}{{/Capitalize}}' => '{{{Capitalize type}}}',
    '{{#MongoDate m/d/Y}}{{created_date}}{{/MongoDate}}' => '{{{MongoDate created_date format="m/d/Y"}}}',
    '{{{id}}}' => '{{{id}}}'."\n".'{{{form-token}}}',
];

$files = assetsRead('./');
$files = array_delete($files, './Manifest.json');
$files = array_delete($files, './Blogs.php');
$files = array_delete($files, './Subcategories.php');
$files = array_delete($files, './Categories.php');
$files = array_delete($files, './managers.php');

//print_r($files);

foreach ($files as $file) {
    $data = file_get_contents($file);
    foreach ($strings as $old => $new) {
        $data = str_replace($old, $new, $data);
    }
    file_put_contents($file, $data);
}
