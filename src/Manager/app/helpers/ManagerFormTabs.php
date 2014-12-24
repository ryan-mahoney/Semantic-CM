<?php
namespace Helper\Manager;

class ManagerFormTabs
{
    public function render(Array $args, Array $options)
    {
        $buffer = '';
        $metadata = $options['metadata'];

        $modifiedMarkup = '<abbr class="time" title="">Not yet saved</abbr>';
        if (!empty($options['modified'])) {
            $modifiedMarkup = '<abbr class="time" data-livestamp="'.$options['modified'].'"></abbr>';
        }

        $buffer .= '
            <div class="ui page grid">
                <div class="row">
                    <div class="column">
                        <div id="manager-form-tabs" class="ui large secondary pointing menu">';

        if (count($metadata['tabs']) == 0) {
            $buffer .= '    <a class="active item bg-image align-left" data-tab="Main">Main</a>';
        }
        $active = 'active align-left ';
        foreach ($metadata['tabs'] as $tab) {
            $buffer .= '    <a class="'.$active.'item" data-tab="'.$tab.'">'.$tab.'</a>';
            $active = '';
        }
        $buffer .= '
                            <div id="manager-form-update">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div id="manager-form-modtime"><i class="time icon"></i> '.$modifiedMarkup.'</div>
                                            </td>
                                            <td>
                                                <div class="ui large primary buttons">
                                                    <div class="ui icon button manager submit">
                                                        <i class="'.$metadata['icon'].' icon"></i> Save '.$metadata['singular'].'
                                                    </div>
                                                    <div class="ui combo top right pointing dropdown icon button">
                                                        <i class="dropdown icon"></i>
                                                        <div class="menu">
                                                            <div class="item" data-value="save-another"><i class="plus icon"></i>Save, Add Another</div>
                                                            <div class="item" data-value="save-copy"><i class="copy icon"></i>Save As Copy</div>
                                                            <div class="item" data-value="save-delete"><i class="delete icon"></i>Delete</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

        return $buffer;
    }
}
