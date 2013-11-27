<?php
return function ($template, $context, $args, $source) {
	$metadata = $context->get('metadata');
	ob_start();
	echo '
		<div class="ui huge breadcrumb container">
			<a class="section" href="/Manager"><h2>Dashboard</h2></a>
			<i class="right arrow icon divider"></i>
			<a class="section" href="/Manager?', $metadata['category'], '"><h2>', $metadata['category'], '</h2></a>
			<i class="right arrow icon divider"></i>
			<a class="section" href="/Manager/list/', $metadata['manager'], '"><h2>', $metadata['title'], '</h2></a>
			<i class="right arrow icon divider"></i>
			<a class="active section"><h2>', $metadata['singular'], '</h2></a>
		</div>
		<div class="ui ignored divider container padding"></div>
		<div class="ui two column grid container padding">
			<div class="column fontSize">
				<p>', (isset($metadata['definition']) ? $metadata['definition'] : ''), '</p>
			</div>
  		</div>
  		<form data-xhr="true" method="post" action="/Manager/manager/' . $metadata['manager'] . '" data-manager="' . $metadata['manager'] . '">
			<!-- <div class="ui warning message">
			    <div class="header">There was a problem</div>
			    <ul class="list"></ul>
			</div> -->';

	$buffer = ob_get_clean();
	return $buffer;
};