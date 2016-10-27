<?php
namespace HessianService59\Parsers\Store59\Dorm\Common\Model;

use HessianService59\Parsers\Parser;

/**
* com.store59.dorm.common.model.Dorm
*/
class DormParser extends Parser
{
    public function parse($options = [])
    {
        parent::parse($options);

        // 移除密码
        if (isset($this->payPasswd)) {
            unset($this->payPasswd);
        }

        return $this;
    }
}
