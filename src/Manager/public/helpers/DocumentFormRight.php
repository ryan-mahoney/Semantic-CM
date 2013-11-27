<?php
return function ($template, $context, $args, $source) {
	$engine = $template->getEngine();
	return '
				<div class="four wide column manager sidebar form">' .
					($source != '' ? $engine->render($source, $context) : '') . '
				</div>
	        </div>
	    </div>';
};