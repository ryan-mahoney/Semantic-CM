<?php
namespace Helper\Manager;

class ManagerEmbeddedFormFooter
{
    public function render(Array $args, Array $options)
    {
        return '
                        </div>
                    </div>
                </div>
                <div class="actions">
                    <div class="ui black button embedded close">Close</div>
                    <button class="ui positive right labeled icon button">Save<i class="checkmark icon"></i></button>
                </div>
            </form>';
    }
}
