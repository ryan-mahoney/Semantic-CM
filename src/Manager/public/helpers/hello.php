<?php
return function ($template, $context, $args, $source) {
	ob_start();
	print_r($context->get('metadata'));
	//echo $template;
	//var_dump($context);
	//var_dump($args);
	//var_dump($source);
	$var = ob_get_clean();
	return $var;
};