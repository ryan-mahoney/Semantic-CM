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
            <div class="right menu align-right">
                <div class="ui teal small buttons">
                	<div class="ui button manager submit">Save</div>
                	<div class="ui floating dropdown icon button top right pointing">
                    	<i class="dropdown icon"></i>
                        <div class="menu">
                            <div class="item" data-value="horizontal flip">Save &amp; Stay</div>
                            <div class="item" data-value="fade up">Save, then Add Another</div>
                            <div class="item" data-value="scale">Save As Copy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

	$buffer = ob_get_clean();
	return $buffer;
};

