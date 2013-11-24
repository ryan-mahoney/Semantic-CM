<?php
return function ($template, $context, $args, $source) {
	ob_start();
	$metadata = $context->get('metadata');
	echo '
		<div class="ui icon message">
    		<i class="ui icon ', $metadata['icon'], '" style="vertical-align: top"></i>
        	<div class="content">
            	<div class="header">This section is currently empty.</div>
              	<p>To add the first “', $metadata['singular'], '”, click the button below.<br /><div class="ui teal medium button manager add">Add</div></p>
         	</div>
		</div>';
	$buffer = ob_get_clean();
	return $buffer;
};