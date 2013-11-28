<?php
return function ($template, $context, $args, $source) {
	$args = str_getcsv(trim($args), ' ');
	$field = array_shift($args);
	$required = false;
	if (in_array('required', $args)) {
		$required = true;
		$args = array_diff($args, array('required'));
	}
	$markup = $context->get($field);
	$label = false;
	$labeled = '';
	if (count($args) > 0) {
		$label = array_shift($args);
		$labeled = ' labeled ';
	}

	ob_start();
	echo '
		<div class="field" data-field="', $field, '">',
			($label !== false ? '<label>' . $label . '</label>' : ''),
            '<div class="ui left ', $labeled, ' input">',
                $markup,
                ($required !== false ? '<div class="ui corner label"><i class="icon asterisk"></i></div>' : ''), 
            '</div>
        </div>';

	$buffer = ob_get_clean();
	return $buffer;
};