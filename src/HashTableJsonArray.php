<?php

namespace RedisAbstract;

use Redis;
use RedisAbstract\Serializer\JsonArray;

class HashTableJsonArray extends HashTableSerializible
{
    /**
     * HashTableJson constructor.
     * @param string $name
     * @param Redis|null $redis
     * @throws Exception\InvalidArgument
     */
    public function __construct(string $name, Redis $redis = null)
    {
        parent::__construct($name, $redis, new JsonArray());
    }
}
