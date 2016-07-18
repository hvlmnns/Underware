<?php

namespace Underware\Plugins;

use Underware\Engine\Events\Event;


class VariablesPlugin extends Event{

    public function dispatch($output)
    {
        $output = preg_replace("/\{\{(.*?)\}\}/","<?php echo \$this->Variables->get('$1'); ?>",$output);
        return $output;
    }

}