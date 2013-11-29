<?php
return function ($template, $context, $args, $source) {
	$metadata = $context->get('metadata');
	ob_start();
	echo '
					</div>
				</div>
			</div>
			<div class="actions">          
				<div class="ui black button embedded close">Close</div>          
				<button class="ui positive right labeled icon button">Save<i class="checkmark icon"></i></button>
			</div>
		</form>';

	$buffer = ob_get_clean();
	return $buffer;
};