<?php
return function ($context, $post, $authentication) {
	if (!isset($context['dbURI']) || empty($context['dbURI'])) {
		throw new \Exception('Context does not contain a dbURI');
	}
	if (!isset($context['formMarker'])) {
		throw new \Exception('Form marker not set in post');
	}
	$document = $post->{$context['formMarker']};
	if ($document === false || empty($document)) {
		throw new \Exception('Document not found in post');
	}
	if (!isset($document['zone'])) {
		$post->errorFieldSet($context['formMarker'], 'Missing zone value.');
		return;
	}
	$try = $authentication->login($document['email'], $document['password']);
	if ($try === false) {
		$post->errorFieldSet($context['formMarker'], 'Credentials do not match. Please check your email or password and try again.');
		return;	
	}
	if (!$authentication->permission($document['zone'])) {
		$post->errorFieldSet($context['formMarker'], 'You do not have access to the area.');
		return;
	}
	$post->statusSaved();
};