<?php
return function ($context, $post, $db, $collection, $search) {
	if (!isset($context['dbURI']) || empty($context['dbURI'])) {
		throw new \Exception('Event does not contain a dbURI');
	}
	if (!isset($context['formMarker'])) {
		throw new \Exception('Form marker not set in post');
	}
	$documentInstance = $db->documentStage($context['dbURI'], []);
	$documentInstance->remove();
	$post->statusDeleted();
	$id = $documentInstance->id();
	$collectionName = $documentInstance->collection();
	$search->delete($id, $collectionName);
};