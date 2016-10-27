<?php

use HessianService59\Helpers\HessianHelpers;
use HessianService59\Services\OrderService\QuerySellerOrder;
use HessianService59\Services\UserService\User;
use HessianService59\Services\PhptestService\PhpApi;

/**
* 测试
*/
class ServiceTest extends TestCase
{

    // public function testPhpTest()
    // {
    //     $a = PhpApi::test();
    //     print_r($a);
    // }
    // public function testOrders() {

    //     $orderQuery = QuerySellerOrder::createOrderQuery([
    //             'withOrderItems' => true
    //         ]);

    //     $orders = QuerySellerOrder::queryOrdersPaging($orderQuery, 5, 1);

    //     $this->assertEquals(1, count($orders));

    //     foreach ($orders as $key => $order) {
    //         $this->assertTrue(is_string($order->status));
    //         $this->assertTrue(is_string($order->createTime));
    //     }
    //     // print_r($orders[0]->toArray());
    // }
    // public function testUser() {

    //     $uid = 1412057569;

    //     $user = User::getUser($uid);
    //     $this->assertObjectNotHasAttribute('passwd', $user);
    // }

    public function testUserListByFilter($value='')
    {
       $userFilter = User::createUserFilter([
        'uids' => [
            123,
            2123423423423
        ]
        ]);

       $users = User::getUserListByFilter($userFilter);

       print_r($users);
    }
}
