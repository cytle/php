<?php
namespace HessianService59\Configs;

use HessianService59\Contracts\ParserContract;
/**
*
*/
class ParseFilters
{
    public function date($ts)
    {
        return date('Y-m-d H:i:s', $ts);
    }

    public function object($obj, $parser)
    {
        if ($obj instanceOf ParserContract) {
            // 进行翻译
            $next = $obj->parse();

            // 如果不是对象或者不是原来的数据，则更新objectlist中数据
            if (! is_object($next) || $next !== $obj ) {
                $index = $parser->refmap->getReference($obj);
                $parser->refmap->objectlist[$index] = $next;
            }

            return $next;
        }

        return $obj;
    }

    public static function getFilters()
    {
        static $filters = null;
        if (is_null($filters)) {
            $filters = new static();
        }
        return [
            'date' => [$filters, 'date'],
            'object' => [$filters, 'object']
        ];
    }
}

