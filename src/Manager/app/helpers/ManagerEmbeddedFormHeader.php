<?php
namespace Helper\Manager;

class ManagerEmbeddedFormHeader
{
    public function render(Array $args, Array $options)
    {
        $metadata = $options['metadata'];

        return '
            <div class="header">'.$metadata['singular'].'</div>
            <form class="manager" data-xhr="true" method="post" action="/Manager/api/upsert/'.$metadata['link'].'" data-manager="'.$metadata['link'].'">
                <div class="ui divided grid">
                    <div class="row">
                        <div class="sixteen wide column manager embedded ui form">';
    }
}
