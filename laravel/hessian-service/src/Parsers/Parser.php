<?php

namespace HessianService59\Parsers;

use HessianService59\Parsers\ResultCasts;
use HessianService59\Contracts\ParserContract;


/**
* 转化基础类
*/
class Parser implements ParserContract
{
    protected $parseRules = [];

    public function parse($options = [])
    {
        if (empty($this->parseRules)) {
            return $this;
        }

        return ResultCasts::objCasts($this, $this->parseRules, $options);
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return (array) $this;
    }

    /**
     * Convert the model to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

}


