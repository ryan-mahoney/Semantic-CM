<?php
return function ($context, $collection) {
    if (!isset($context['dbURI']) || empty($context['dbURI'])) {
        throw new \Exception('Context does not contain a dbURI');
    }
    $collection->statsSet($context['dbURI']);
};