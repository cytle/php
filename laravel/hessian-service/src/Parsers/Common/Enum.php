<?php

namespace HessianService59\Parsers\Common;

use HessianService59\Parsers\Parser;


/**
*
*/
class Enum extends Parser
{
    public function parse($options = [])
    {
        if (isset($this->name)) {
            return $this->name;
        }

        return $this;
    }

}
