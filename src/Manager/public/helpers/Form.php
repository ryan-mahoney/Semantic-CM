<?php
return function ($template, $context, $args, $source) {
    $engine = $template->getEngine();
    $idSpare = $engine->render('{{id_spare}}', $context);
    $metadata = $context->get('metadata');
    return '
          <form data-xhr="true" method="post" data-idSpare="' . $idSpare . '" data-titleField="' . $metadata['titleField'] . '" data-singular="' . $metadata['singular'] . '" action="/Manager/manager/' . $metadata['manager'] . '" data-manager="' . $metadata['manager'] . '">
              <input type="submit" style="position: absolute; visibility: hidden" />';
};