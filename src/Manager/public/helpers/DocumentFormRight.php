<?php
return function ($template, $context, $args, $source) {
	$engine = $template->getEngine();
	return '
						<div class="two wide column manager sidebar">' .
							($source != '' ? $engine->render($source, $context) : '') . '
						</div>
			        </div>
			    </div>
			</div>
		</form>';
};