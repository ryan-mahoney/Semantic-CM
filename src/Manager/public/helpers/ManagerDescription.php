<?php
return function ($template, $context, $args, $source) {
    $engine = $template->getEngine();
    return $engine->render($context->get('description'), $context);
};