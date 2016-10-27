<?php
namespace HessianService59\Services;


use HessianService59\ServiceBuilder;

/**
 * 详见 __callStatic 注释
 * @author xsp <chensp@59sotre.com>
 *
 */
class Service {

    /**
     * 当前ServicApi 的名字，根据services.php 中配置
     */
    const SERVICE_API_NAME = null;

    /**
     * 当前ServicApi 的查询路径
     */
    const SERVICE_API_PATH = null;

    /**
     * 获取service的默认 api url，值根据定义的常量SERVICE_API_NAME和SERVICE_API_PATH获取
     *
     * @return string
     */
    protected static function getDefaultUrl()
    {
        return ServiceBuilder::getServiceBuilder()
            ->getFullApiUrl(static::SERVICE_API_NAME, static::SERVICE_API_PATH);
    }

    /**
     * 魔法方法，当没有静态方法时，默认调用Service，url为getDefaultUrl()，method为被调用
     * 的方法名，参数为调用的参数列表。
     *
     */
    public static function __callStatic ($name , $arguments)
    {
        return ServiceBuilder::getServiceBuilder()
            ->queryDataSuccessOrFail(static::getDefaultUrl(), $name, $arguments);
    }
}
