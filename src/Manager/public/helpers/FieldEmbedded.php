<?php
return function ($template, $context, $args, $source) {
	$args = explode(' ', trim($args));
	$field = array_shift($args);
	$manager = array_shift($args);
	$markup = $context->get($field);

	return '<div class="field embedded" data-field="' . $field . '" data-manager="' . $manager . '">XXX' . $markup . '</div>';
};