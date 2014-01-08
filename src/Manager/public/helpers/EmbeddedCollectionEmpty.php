<?php
return function ($template, $context, $args, $source) {
	//usage: {{#EmbeddedCollectionEmpty singular="Category"}}
	$args = $template->htmlArgsToArray($args);
	$singular = $args['singular'];
	ob_start();
	echo '
		<div class="ui message">
			<div class="content">
            	<div class="header">This section is currently empty.</div>
              	<p>To add the first “', $singular, '”, click the button on the right.</p>
         	</div>
		</div>';
	$buffer = ob_get_clean();
	return $buffer;
};