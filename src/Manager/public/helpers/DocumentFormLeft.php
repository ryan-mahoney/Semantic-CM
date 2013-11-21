<?php
return function ($template, $context, $args, $source) {
	$engine = $template->getEngine();
	return '
		<form data-xhr="true" method="post" action="/Manager/manager/menus">
			<div class="bottom-container">
				<div class="ui divided grid">
			        <div class="row">
			            <div class="ten wide column manager main">' .
							($source != '' ? $engine->render($source, $context) : '') . '
						</div>';
};