<?php

namespace RedisAbstract;

use Redis;
use RedisAbstract\Exception\LogicException;
use RedisAbstract\Serializer\SerializerInterface;

abstract class HashTableSerializible extends HashTable
{
    /**
     * HashTableSerializible constructor.
     * @param string $name
     * @param Redis|null $redis
     * @param SerializerInterface $serializer
     * @throws Exception\InvalidArgument
     */
    public function __construct(string $name, Redis $redis = null, SerializerInterface $serializer)
    {
        $this->setSerializer($serializer);
        parent::__construct($name, $redis);
    }

    /**
     * @inheritdoc
     * @throws LogicException
     * @internal
     */
    public function increment(string $key, $byAmount = 1): int
    {
        return $this->stub(__METHOD__);
    }

    /**
     * @inheritdoc
     * @throws LogicException
     * @internal
     */
    public function incrementByFloat(string $key, $byAmount = 1): string
    {
        return $this->stub(__METHOD__);
    }

    /**
     * @param $name
     * @throws LogicException
     */
    private function stub($name)
    {
        throw new LogicException('Method '.$name.' forbidden');
    }
}
