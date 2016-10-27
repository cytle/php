<?php

use HessianService59\Configs\ServiceApiConfigMake;

$configMaker = new ServiceApiConfigMake();

return [
    'api' => $configMaker->getConfig(),

    'hessian' => [
        'typeMap' => [
            // 隐藏用户密码
            'HessianService59\Parsers\Store59\User\Common\Model\UserParser' => 'com.store59.user.common.model.User',

            // 隐藏店长支付密码
            'HessianService59\Parsers\Store59\Dorm\Common\Model\DormParser' => 'com.store59.dorm.common.model.Dorm',

            'HessianService59\Parsers\Hessian\IO\ByteHandle' => 'com.caucho.hessian.io.ByteHandle',
            'HessianService59\Parsers\Hessian\IO\ShortHandle' => 'com.caucho.hessian.io.ShortHandle',
            'HessianService59\Parsers\Hessian\IO\FloatHandle' => 'com.caucho.hessian.io.FloatHandle',

            'HessianService59\Parsers\Common\Enum' => '*Enum',

            'HessianService59\Parsers\Parser' => '*',
        ],
    ]
]

?>
