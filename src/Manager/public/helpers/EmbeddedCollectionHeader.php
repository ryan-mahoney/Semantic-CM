<?php
return function ($template, $context, $args, $source) {
	//usage: {{#EmbeddedCollectionHeader label="Some Label"}}
	$args = $template->htmlArgsToArray($args);
	$label = $args['label'];
	echo '
		<a class="item">', $label, '</a>
		<div class="item right">
    		<div class="ui button manager add">Add</div>
		</div>';

	$buffer = ob_get_clean();
	return $buffer;
};