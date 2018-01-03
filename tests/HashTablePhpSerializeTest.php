<?php

namespace Tests;

use PHPUnit_Framework_AssertionFailedError;
use RedisAbstract\HashTablePhpSerialize;

class HashTablePhpSerializeTest extends AbstractTestCase
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
            'four' => new \DateTime(),
        ];
        $set = new HashTablePhpSerialize('TestHash:'.uniqid('', true), $redis);

        foreach ($data as $key => $value) {
            $this->assertTrue($set->set($key, $value));
            $this->assertEquals($value, $set->get($key));
            $this->assertEquals($value, $set[$key]);
        }
        $this->assertEquals(count($data), count($set->getData()));
        $this->assertEquals(count($data), $set->getCount());
        $this->assertEquals(count($data), $set->getCount(true));

    }
}