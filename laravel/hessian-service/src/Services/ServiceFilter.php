<?php

namespace HessianService59\Services;

use LibHessian\HessianHelpers;

use HessianService59\BasicWrite\Long;
/**
 * ServiceFilterrrrrrrrrrr
 * @author gjy <goujy@59store.com>
 */
class ServiceFilter {

    /**
     * default value
     * @var array
     */
    protected $__casts = [];

    function __construct(array $data) {
        if ( isset( $this->__required ) && is_array( $this->__required ) ) {
            foreach ( $this->__required as $v ) {
                if ( ! isset( $data[$v] ) ) {
                    throw new \Exception('Attr ' . $v . ' is required to new ' . get_class($this));
                }
            }
        }

        foreach ($data as $key => $value) {
            // 映射java类，不能通过这种方式修改
            if ($key === '__type') {
                continue;
            }
            if ( $this->hasCast($key) ) {
                $this->{$key} = static::parse( $this->getCastType($key), $value );
            } else {
                $this->{$key} = $value;
            }
        }

        // 销毁额外的成员
        unset($this->__casts, $this->__required);
    }

    protected function hasCast($key) {
        return array_key_exists($key, $this->__casts);
    }

    protected function getCastType($key) {
        return trim( $this->__casts[$key] );
    }


    /**
     * 获取转化后的值
     */
    public static function parse( $type, $value ) {
        $typeInfo = static::parseType($type);

        return static::cast($typeInfo, $value);
    }



    /**
     * @param 类型说明字符串
     * @return array
     * [
     *     'type'       => $type,        // 基本类型
     *     'remoteType' => $remoteType,  // 远程类型 默认null
     *     'subType'    => $subType,     // 子级，在基本类型为list时存在，默认为null
     * ]
     ***********************
     * 栗子（`List`同`list`）
     * int list list<int> list<enum:asd.asd> enum:asd.asd
     *
     * parseType('int');
     * [ 'type' => 'int', 'remoteType' => null, 'subType' => null ]
     *
     * parseType('list');
     * [ 'type' => 'list', 'remoteType' => null, 'subType' => null ]
     *
     * parseType('enum:com.store.dorm.dorm');
     * [ 'type' => 'list', 'remoteType' => 'com.store.dorm.dorm', 'subType' => null ]
     *
     * parseType('list<int>');
     * [
     *      'type'       => 'list',
     *      'remoteType' => null,
     *      'subType'    => [
     *          'type'       => 'int',
     *          'remoteType' => null,
     *          'subType'    => null
     *      ]
     *  ]
     *
     * parseType('list<enum:asd.asd>');
     * [
     *      'type'       => 'list',
     *      'remoteType' => null,
     *      'subType'    => [
     *          'type'       => 'enum',
     *          'remoteType' => 'asd.asd',
     *          'subType'    => null
     *      ]
     *  ]
     *
     */
    public static function parseType($type)
    {
        $remoteType = null;
        $subType = null;

        if (preg_match('/^[lL]ist\<([\w\.\:\<\>]*)\>$/', $type, $matches)) {
            $type = 'list';
            $subType = static::parseType($matches[1]);
        } else if (preg_match('/^([\w\.]*?):([\w\.]*)$/', $type, $matches)) {
            $type = $matches[1];
            $remoteType = $matches[2];
        }

        return [
            'type' => strtolower($type),
            'remoteType' => $remoteType,
            'subType' => $subType,
        ];
    }

    /**
     * 根据类型信息进行转化
     */
    protected static function cast($typeInfo, $value)
    {
        $type = $typeInfo['type'];

        if (is_null($type)) {
            // TODO throw
            return $value;
        }

        $fun = 'static::cast' . ucfirst(strtolower($type));

        if (is_callable($fun)) {
            return call_user_func($fun, $value, $typeInfo);
        }

        return $value;
    }

    protected static function castInt($value)
    {
        return (int) $value;
    }

    protected static function castInteger($value)
    {
        return static::castInt($value);
    }

    protected static function castLong($value)
    {
        return HessianHelpers::createLong($value);
    }

    protected static function castByte($value)
    {
        return static::castInt($value);
    }

    protected static function castBoolean($value)
    {
        return (boolean) $value;
    }

    protected static function castDouble($value)
    {
        return (double) $value;
    }

    protected static function castFloat($value)
    {
        return (float) $value;
    }

    protected static function castDatetime($value)
    {
        return HessianHelpers::createDateTime($value);
    }

    protected static function castDate($value)
    {
        return static::castDatetime($value);
    }

    protected static function castEnum($value, $typeInfo)
    {
        if ( is_string( $value ) ) {
            return HessianHelpers::createEnum($value, $typeInfo['remoteType']);
        }
        else if ( $value instanceof \Enum59\Enum ) {
            return $value->toEnum($typeInfo['remoteType']);
        }

        return $value;
    }

    protected static function castArray($value)
    {
        return (array) $value;
    }

    protected static function castList($value, $typeInfo)
    {
        $value = static::castArray($value);

        $subType = $typeInfo['subType'];

        if (is_null($subType)) {
            return $value;
        }

        return array_map(function ($item) use ($subType) {
            return static::cast($subType, $item);
        }, $value);
    }



}
