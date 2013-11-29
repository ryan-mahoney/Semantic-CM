<?php
return function ($template, $context, $args, $source) {
	$args = str_getcsv(trim($args), ' ');
	$label = array_shift($args);
	echo '
		<a class="item">', $label, '</a>
		<div class="item right">
    		<div class="ui button manager add">Add</div>
		</div>';

	$buffer = ob_get_clean();
	return $buffer;
};