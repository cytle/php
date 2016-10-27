<?php
namespace HessianService59\Services\StockService;

use HessianService59\Services\Service;

class DormWarehouse extends Service {

    const SERVICE_API_NAME = 'stock';
    const SERVICE_API_PATH = '/dormwarehouse';

    public static function createDormWarehouseFilter(array $data) {
        return new Filters\DormWarehouseFilter($data);
    }
}
