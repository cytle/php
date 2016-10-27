<?php
namespace HessianService59\Parsers\Hessian\IO;

use HessianService59\Parsers\Parser;

/**
* com.caucho.hessian.io.ShortHandle
*/
class ShortHandle extends Parser
{
    public function parse($options = [])
    {
        return object_get($this, '_value');
    }
}
