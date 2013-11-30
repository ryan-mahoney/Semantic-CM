<?php
return function ($template, $context, $args, $source) {
	$engine = $template->getEngine();
    $modifiedDate = $engine->render('{{modified_date}}', $context);
    $modifiedMarkup = '<abbr class="time" title="">Not yet saved</abbr>';
    if (!empty($modifiedDate)) {
        $modifiedMarkup = '<abbr class="time" data-livestamp="' . $modifiedDate . '"></abbr>';
    }
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
            <span><i class="time icon"></i> ', $modifiedMarkup, '</span>
        </div>';

	$buffer = ob_get_clean();
	return $buffer;
};