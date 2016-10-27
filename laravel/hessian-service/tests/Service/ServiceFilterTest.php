<?php

use HessianService59\Services\ServiceFilter;

/**
* 测试
*/
class ServiceFilterTest extends TestCase
{
    public function testParseType() {

        $type = 'list';
        $type = ServiceFilter::parseType($type);

        $this->assertEquals('list', $type['type']);
        $this->assertNull($type['subType']);
        $this->assertNull($type['remoteType']);


        $type = 'list:asd';
        $type = ServiceFilter::parseType($type);

        $this->assertEquals('list', $type['type']);
        $this->assertNull($type['subType']);
        $this->assertEquals('asd', $type['remoteType']);


        $type = 'list<asd.asdzxc<asd>>';
        $type = ServiceFilter::parseType($type);

        $this->assertEquals('list', $type['type']);

        $subType = $type['subType'];

        $this->assertNotNull($subType);
        $this->assertEquals('asd.asdzxc<asd>', $subType['type']);
        $this->assertNull($subType['subType']);
        $this->assertNull($subType['remoteType']);

        $this->assertNull($type['remoteType']);
    }

    public function testParse() {

        $type = 'list';
        $value = 'asd';
        $result = ServiceFilter::parse($type, $value);

        $this->assertArraySubset([$value], $result);

    }



}
