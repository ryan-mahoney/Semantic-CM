<?php
return function ($template, $context, $args, $source) {
	ob_start();
	$metadata = $context->get('metadata');

	echo '
		<div class="manager table buttons">
			<!-- <div class="ui small button export">Export</div> -->
			<!-- <div class="ui small button filter">Filter</div> -->
			<div class="ui teal small button manager add">Add</div>
		</div>';

	$buffer = ob_get_clean();
	return $buffer;
};