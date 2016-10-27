<?php
namespace HessianService59\Parsers\Store59\Order\Common\Service\Dto;

use HessianService59\Parsers\Parser;

/**
* com.store59.order.common.service.dto.OrderDTO
*/
class OrderDTOParser extends Parser
{
    protected $parseRules = [
                'buyerRemark'   => 'SimpleUTF8',
            ];
}
