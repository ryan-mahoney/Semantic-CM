<?php
return function ($template, $context, $args, $source) {
	$metadata = $context->get('metadata');
	ob_start();
	echo '
  		<form data-xhr="true" method="post" data-titleField="', $metadata['titleField'], '" data-singular="', $metadata['singular'], '" action="/Manager/manager/' . $metadata['manager'] . '" data-manager="' . $metadata['manager'] . '">
  			<input type="submit" style="position: absolute; visibility: hidden" />';
	$buffer = ob_get_clean();
	return $buffer;
};