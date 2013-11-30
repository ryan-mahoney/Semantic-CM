<?php
return function ($template, $context, $args, $source) {
	$metadata = $context->get('metadata');
	$engine = $template->getEngine();
	return '
		<div class="ui divided grid">
	        <div class="row">
	            <div class="eleven wide column manager main ui form">' .
					($source != '' ? $engine->render($source, $context) : '') . '
				</div>';
};