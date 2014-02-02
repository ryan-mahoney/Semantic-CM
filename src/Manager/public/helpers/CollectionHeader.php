<?php
return function ($template, $context, $args, $source) {
    $buffer = '';
    $metadata = $context->get('metadata');
    $pagination = $context->get('pagination');
    $stop = $pagination['page'] * $pagination['limit'];
    if ($pagination['total'] < $stop) {
        $stop = $pagination['total'];
    }
    $start = (($pagination['page'] -1) * $pagination['limit']) + 1;
    $buffer .= '
        <div class="ui huge breadcrumb container">
            <a class="section" href="/Manager"><h2>Dashboard</h2></a><i class="right arrow icon divider"></i>
            <a class="section" href="/Manager?' . $metadata['category'] . '"><h2>' . $metadata['category'] . '</h2></a>
            <i class="right arrow icon divider"></i>
            <a class="active section"><h2>' . $metadata['title'] . '</h2></a>
        </div>
        <div class="ui ignored divider container padding"></div>
        <div class="ui two column grid container padding">
            <div class="column fontSize">
                <p>' . (isset($metadata['definition']) ? $metadata['definition'] : '') . '</p>
            </div>';
    if ($pagination['total'] > 0) {
        $buffer .= '
            <div id="right" class="column fontSize">
                <p>' . $start . '-' . $stop . ' of ' . $pagination['total'] . ' 
            </div>';
    }
    $buffer .= '
          </div>
        <div class="ui ignored divider"></div>';

    return $buffer;
};