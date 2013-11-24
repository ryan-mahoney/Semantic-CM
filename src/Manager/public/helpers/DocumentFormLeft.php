<?php
return function ($template, $context, $args, $source) {
	$metadata = $context->get('metadata');
	$engine = $template->getEngine();
	return '
		<form data-xhr="true" method="post" action="/Manager/manager/' . $metadata['manager'] . '" data-manager="' . $metadata['manager'] . '">
			<!-- <div class="ui warning message">
			    <div class="header">There was a problem</div>
			    <ul class="list"></ul>
			</div> -->
			<div class="ui divided grid">
		        <div class="row">
		            <div class="twelve wide column manager main ui form">' .
						($source != '' ? $engine->render($source, $context) : '') . '
					</div>';
};