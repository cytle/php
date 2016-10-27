<?php

namespace HessianService59\Services\StockService\Filters;

use HessianService59\Services\ServiceFilter;

/**
 * DormWarehouseFilter
 */
class DormWarehouseFilter extends ServiceFilter {
    /**
     *
     * @var List<String>
     */
    public $idList;

    /**
     * 库存中心供货商ID
     * @var String
     */
    public $whId;

    /**
     *
     * @var BusiTypeEnum
     */
    public $busType;

    /**
     * 店长ID
     * @var Integer
     */
    public $dormId;

    /**
     * 学校ID
     * @var Integer
     */
    public $siteId;

    /**
     * 学校ID LIST
     * @var List<Integer>
     */
    public $siteIdList;

    /**
     * 价格表ID
     * @var String
     */
    public $cateId;

    /**
     * @var List<Integer>
     */
    public $dormIdList;

    /**
     * @var List<String>
     */
    public $whIdList;

    /**
     * @var Integer
     */
    public $offset;

    /**
     * @var Integer
     */
    public $limit;

    /**
     * @var List<Byte>
     */
    public $busiTypeList;

    /**
     * 是否过滤掉dorm_id=0的记录
     * @var Boolean
     * 默认 Boolean.TRUE
     */
    public $filterZeroDorm = true;

    /**
     * 经销商联系方式
     * @var String
     */
    public $phone;

    /**
     * (默认为可以为店长补货的供货商,false:对应的全部经销商）
     * @var Boolean
     * 默认 true
     */
    public $available = true;


    protected $__casts = [
        'idList'         => 'List<String>',
        'whId'           => 'String',
        'busType'        => 'enum:com.store59.stock.common.enums.BusiTypeEnum',
        'dormId'         => 'Integer',
        'siteId'         => 'Integer',
        'siteIdList'     => 'List<Integer>',
        'cateId'         => 'String',
        'dormIdList'     => 'List<Integer>',
        'whIdList'       => 'List<String>',
        'offset'         => 'Integer',
        'limit'          => 'Integer',
        'busiTypeList'   => 'List<Byte>',

        'filterZeroDorm' => 'Boolean',
        'phone'          => 'String',
        'available'      => 'Boolean',
    ];
}
