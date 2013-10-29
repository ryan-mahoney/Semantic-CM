<?php
return function ($context, $post, $db) {
	if (!isset($context['dbURI']) || empty($context['dbURI'])) {
		throw new \Exception('Conext does not contain a dbURI');
	}
	if (!isset($context['formMarker'])) {
		throw new \Exception('Form marker not set in post');
	}
	$document = $post->{$context['formMarker']};
	if ($document === false || empty($document)) {
		throw new \Exception('Document not found in post');
	}
	print_r($document);
	exit;
	//$documentObject = $db->documentStage($context['dbURI'], $document);
	//$documentObject->upsert();
	$post->statusSaved();
};