<?php
return function ($template, $context, $args, $source) {
	$args = str_getcsv(trim($args), ' ');
	$field = array_shift($args);
	$required = false;
	if (in_array('required', $args)) {
		$required = true;
		$args = array_diff($args, array('required'));
	}
	$manager = array_shift($args);
	$markup = $context->get($field);
	$label = false;
	if (count($args) > 0) {
		$label = array_shift($args);
	}
	if (empty($markup)) {
		return '<div class="field embedded" data-field="' . $field . '" data-manager="' . $manager . '"><div class="ui message">' . $label . ' can be added after save.</div></div>';
	}

	return '<div class="field embedded" data-field="' . $field . '" data-manager="' . $manager . '">' . $markup . '</div>';
};