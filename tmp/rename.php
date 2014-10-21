<?php
$helpers = [
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
	'Field' => 'ManagerField',
	'Form' => 'ManagerForm'
];

foreach ($helpers as $helperOld => $helperNew) {
	echo 'mv ./', $helperOld . '.php', ' ', $helperNew, '.php', "\n";
}