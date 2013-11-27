<?php
return function ($template, $context, $args, $source) {
	ob_start();
	$metadata = $context->get('metadata');
	echo '
		<div class="ui top attached tabular menu container">';

    if (count($metadata['tabs']) == 0) {
        echo '<a class="active item bg-image align-left" data-tab="Main">Main</a>';
    }
    $active = 'active align-left ';
    foreach ($metadata['tabs'] as $tab) {
    	echo '<a class="', $active, 'item bg-image" data-tab="', $tab, '">', $tab, '</a>';
        $active = '';
    }
               
    echo '
        </div>';

	$buffer = ob_get_clean();
	return $buffer;
};