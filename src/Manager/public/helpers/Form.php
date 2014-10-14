<?php
return function ($args, $named) {
    $idSpare = $named['spare'];
    $metadata = $named['metadata'];
    return '
        <form data-xhr="true" method="post" data-idSpare="' . $idSpare . '" data-titleField="' . $metadata['titleField'] . '" data-singular="' . $metadata['singular'] . '" action="/Manager/upsert/' . $metadata['link'] . '" data-manager="' . str_replace('\\', '__', $metadata['namespace']) . '__' . $metadata['manager'] . '">
            <input type="submit" style="position: absolute; visibility: hidden" />';
};