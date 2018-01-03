<?php

namespace Tests;

use PHPUnit_Framework_AssertionFailedError;
use RedisAbstract\HashTableJsonArray;

class HashTableJsonArrayTest extends AbstractTestCase
{
    /**
     * Tests the basic functionality
     * @throws PHPUnit_Framework_AssertionFailedError
     * @throws \Exception
     */
    public function testBasics()
    {
        $redis = $this->redis;

        $data = [
            'one' => [1.40],
            'two' => ['price' => 2.40],
            'three' => ['null' => null, 'false' => false, 'true' => true, '1' => 1, '0' => 0, 'string' => 'some_text'],
            'four' => null,
        ];
        $set = new HashTableJsonArray('TestHash:'.uniqid('', true), $redis);

        foreach ($data as $key => $value) {
            $this->assertTrue($set->set($key, $value));
            $this->assertSame($value, $set->get($key));
            $this->assertSame($value, $set[$key]);
        }
        $this->assertEquals(count($data), count($set->getData(true)));
        $this->assertEquals(count($data), count($set));
        $this->assertEquals(count($data), $set->getCount());
        $this->assertEquals(count($data), $set->getCount(true));

    }
}