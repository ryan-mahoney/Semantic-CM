<?php
return function ($template, $context, $args, $source) {
	//usage: {{FieldEmbedded field="parent-field-name" required="true" manager="name-of-manager-to-embed" label="what to display"}}
	$args = $template->htmlArgsToArray($args);
	$field = $args['field'];
	$required = false;
	if (isset($args['required']) && $args['required'] == true) {
		$required = true;
	}
	$manager = $args['manager'];
	$markup = $context->get($field);
	$label = false;
	if (isset($args['label'])) {
		$label = $args['label'];
	}
	if (empty($markup)) {
		return '<div class="field embedded" data-field="' . $field . '" data-manager="' . $manager . '"><div class="ui message">' . $label . ' can be added after save.</div></div>';
	}

	return '<div class="field embedded" data-field="' . $field . '" data-manager="' . $manager . '">' . $markup . '</div>';
};