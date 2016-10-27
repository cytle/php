<?php
namespace HessianService59\Parsers\Hessian\IO;

use HessianService59\Parsers\Parser;

/**
* com.caucho.hessian.io.FloatHandle
*/
class FloatHandle extends Parser
{
    public function parse($options = [])
    {
        return object_get($this, '_value');
    }
}
