<?php
namespace HessianService59\Parsers\Store59\User\Common\Model;

use HessianService59\Parsers\Parser;

/**
* com.store59.user.common.model.User
*/
class UserParser extends Parser
{
    public function parse($options = [])
    {
        parent::parse($options);

        // 移除密码
        if (isset($this->passwd)) {
            unset($this->passwd);
        }

        return $this;
    }
}
