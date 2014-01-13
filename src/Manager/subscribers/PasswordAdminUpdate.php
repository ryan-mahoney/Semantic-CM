<?php
return function ($context, $post, $db, $collection, $authentication) {
	if (!isset($context['dbURI']) || empty($context['dbURI'])) {
		throw new \Exception('Event does not contain a dbURI');
	}
	if (!isset($context['formMarker'])) {
		throw new \Exception('Form marker not set in post');
	}
	$document = $post->{$context['formMarker']};
	if ($document === false || empty($document)) {
		throw new \Exception('Document not found in post');
	}
	if (isset($document['password2']) && !empty($document['password2'])) {
		$password = $authentication->passwordHash($document['password2']);
		$db->documentStage($document['id'], [
			'password' => $password,
			'password2' => ''
		])->upsert();
	}
};