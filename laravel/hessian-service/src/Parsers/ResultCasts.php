<?php

namespace HessianService59\Parsers;

use DateTime;

/**
 *******************************************************************************
 * 结果转换
 * TODO 测试用例！！！！！
 *
 *************************************************
 * @author xsp <chensp@59sotre.com>
 *
 *************************************************
 *  // objCasts 使用例子
 *  ResultCasts::objCasts($order, [
 *          'createTime'   => 'DateTime',
 *          'updateTime'   => 'DateTime',
 *          'type'         => 'Enum',
 *          'status'       => 'Enum',
 *          'source'       => 'Enum',
 *          'payStatus'    => 'Enum',
 *          'refundStatus' => 'Enum',
 *          'buyerRemark'  => 'SimpleUTF8',
 *          'evaluateScore'=> 'ByteHandle'
 *      ],
 *      [
 *          'format' => 'Y-m-d H:i:s'
 *      ]);
 *
 */
class ResultCasts
{

    /**
     * 转换一个对象
     *
     * @param {object} $obj 原对象
     * @param {array} $castTypes 各属性现在的状态
     * @param {array} $options 配置，将传递到cast中
     *
     * @return {mixed} 对象
     */
    public static function objCasts($obj, array $castTypes, array $options = [])
    {
        if (is_object($obj)) {

            foreach ($castTypes as $name => $type) {

                $value = object_get($obj, $name, null); // 没有值，则设置为null

                // 判断是否有值
                if (is_null($value)) {
                    continue;
                }

                $value = static::casts($type, $value, $options);

                // 设置object值，默认$name 路径是可行的
                $obj = static::object_set($obj, $name, $value);

            }
        }

        return $obj;
    }

    /**
     * 设置object值，默认$name 路径是可行的
     *
     * @param $obj 需要设置的obj
     * @param $name 属性名称，支持多层次，比如 a.b.c 指obj下a属性的b属性的c属性
     * @param $value 值
     *
     * @return mixed
     */
    private static function object_set($obj, $name, $value = null)
    {
        if ($name === '' || $name === null) {
            $obj = $value;
        } else {

            // 按点切割
            $subPropNames = explode('.', $name);

            // 提取最后一个属性名，备用
            $lastSubPropNames = array_pop($subPropNames);

            $deep = count($subPropNames);

            if ($deep > 0) {
                $subObj = object_get($obj, implode('.', $subPropNames));
            } else {
                $subObj = $obj;
            }

            $subObj->{$lastSubPropNames} = $value;
        }



        return $obj;
    }


    /**
     * 转换类型
     *
     * @param {string} $type 目标类型
     * @param {mixed} $value 原值
     * @param {array} $options 配置，将传递到具体方法中
     *
     * @return {mixed} 转换后的值
     */
    public static function casts($type, $value, array $options = [])
    {
        $type = trim(strtolower($type));

        $funArr = [
            'enum'       => 'static::castsEnum',
            'datetime'   => 'static::castsDateTime',
            'simpleutf8' => 'static::castsSimpleUTF8',
            'bytehandle' => 'static::castsByteHandle',
            'shorthandle' => 'static::castsShortHandle',
            'floathandle' => 'static::castsFloatHandle',

        ];

        if (! isset($funArr[$type])) {
            // TODO 抛出异常
            return $value;
        }

        return call_user_func($funArr[$type], $value, $options);

    }


    /**
     * 转换Enum类型
     */
    public static function castsEnum($value)
    {
        if (! is_object($value)) {
            $value = (object) $value;
        }

        return object_get($value, 'name');
    }


    /**
     * 转换ByteHandle类型
     */
    public static function castsByteHandle($value)
    {

        if (! is_object($value)) {
            $value = (object) $value;
        }

        return object_get($value, '_value');

    }


    /**
     * 转换ShortHandle类型
     */
    public static function castsShortHandle($value)
    {

        if (! is_object($value)) {
            $value = (object) $value;
        }

        return object_get($value, '_value');

    }

    /**
     * 转换 FloatHandle 类型
     */
    public static function castsFloatHandle($value)
    {

        if (! is_object($value)) {
            $value = (object) $value;
        }

        return object_get($value, '_value');

    }


    /**
     * 转换dateTime类型
     */
    public static function castsDateTime($value, array $options)
    {

        if ($value instanceof DateTime) {
            if (isset($options['format'])) {
                $format = $options['format'];
            } else {
                $format = 'Y-m-d H:i:s';
            }

            return $value->format($format);
        } else {
            return null;
        }
    }

    /**
     * 去除utf8中的表情等
     *
     */
    public static function castsSimpleUTF8($value)
    {
        if (! is_string($value)) {
            $value = json_encode($value);
        }

        return iconv('GBK', 'UTF-8//IGNORE', iconv('UTF-8', 'GBK//IGNORE', $value));
    }

}
