<?php
return function ($context, $post, $db, $collection, $search) {
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
    $documentInstance = $db->documentStage($context['dbURI'], $document);
    $documentInstance->upsert();
    $post->statusSaved();
    $document = $documentInstance->current();
    $id = $documentInstance->id();
    $collectionName = $documentInstance->collection();
    $collectionInstance = $collection->factory($collectionName);
    if ($collectionInstance === false) {
        return;
    }
    $collectionInstance->index($search, $id, $document);
    $collectionInstance->views('upsert', $id, $document);
    $collectionInstance->statsUpdate($context['dbURI']);
};