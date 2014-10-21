<?php
$strings = [
	'CollectionButtons' => 'ManagerIndexButtons',
	'CollectionEmpty' => 'ManagerIndexBlankSlate',
	'CollectionHeader' => 'ManagerIndexHeader',
	'CollectionPagination' => 'ManagerIndexPagination',
	'DocumentButton' => 'ManagerFormButton',
	'DocumentFormLeftClose' => 'ManagerFormMainColumnClose',
	'DocumentFormLeft' => 'ManagerFormMainColumn',
	'DocumentFormRightClose' => 'ManagerFormSideColumnClose',
	'DocumentFormRight' => 'ManagerFormSideColumn',
	'DocumentHeader' => 'ManagerFormHeader',
	'DocumentTabs' => 'ManagerFormTabs',
	'EmbeddedCollectionEmpty' => 'ManagerEmbeddedIndexEmpty',
	'EmbeddedCollectionHeader' => 'ManagerEmbeddedIndexHeader',
	'EmbeddedFooter' => 'ManagerEmbeddedFormFooter',
	'EmbeddedHeader' => 'ManagerEmbeddedFormHeader',
	'FieldEmbedded' => 'ManagerFieldEmbedded',
	'{{{Field' => '{{{ManagerField',
	'Form' => 'ManagerForm'
];

$files = [
	'./Blogs.php',
	'./Subcategories.php',
	'./Categories.php'
];

foreach ($files as $file) {
	$data = file_get_contents($file);
	foreach ($strings as $old => $new) {
		$data = str_replace($old, $new, $data);
	}
	//echo $data, "\n\n\n";
	file_put_contents($file, $data);
}