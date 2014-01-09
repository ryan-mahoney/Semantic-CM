<?php
return function ($template, $context, $args, $source) {
	$metadata = $context->get('metadata');
	return '
		<div class="header">' . $metadata['singular'] . '</div>
    	<form data-xhr="true" method="post" action="/Manager/manager/' . $metadata['manager'] . '" data-manager="' . $metadata['manager'] . '">
    		<div class="ui divided grid">
	        	<div class="row">
	            	<div class="sixteen wide column manager embedded ui form">';
};