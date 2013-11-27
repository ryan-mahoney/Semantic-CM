<?php
return function ($template, $context, $args, $source) {
	ob_start();
    echo '
        <div class="ui teal medium buttons submit">
         	<div class="ui button manager submit">Save</div><div class="ui floating dropdown icon button top right pointing">
            	<i class="dropdown icon"></i>
                <div class="menu">
                    <div class="item" data-value="horizontal flip">Save &amp; Stay</div>
                    <div class="item" data-value="fade up">Save, then Add Another</div>
                    <div class="item" data-value="scale">Save As Copy</div>
                </div>
            </div>
        </div>';

	$buffer = ob_get_clean();
	return $buffer;
};