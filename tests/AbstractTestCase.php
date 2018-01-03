<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Redis;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @var Redis
     */
    protected $redis;

    protected function setUp()
    {
        $this->redis = new Redis();
        $this->redis->connect('localhost');
    }

}