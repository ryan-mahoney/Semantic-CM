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
            <span><i class="time icon"></i> ', $modifiedMarkup, '</span>
            <div class="ui button manager submit">Save</div><div class="ui teal dropdown icon button">
                <i class="dropdown icon"></i>
                <div class="menu">
                    <div class="item" data-value="save-another"><i class="plus icon"></i>Save, Add Another</div>
                    <div class="item" data-value="save-copy"><i class="copy icon"></i>Save As Copy</div>
                    <div class="item" data-value="save-delete"><i class="delete icon"></i>Delete</div>
                </div>
            </div>
        </div>';

	$buffer = ob_get_clean();
	return $buffer;
};