<?php

namespace RedisAbstract\Serializer;

interface SerializerInterface
{
    /**
     * @param $value
     * @return string
     */
    public function serialize($value): string;

    /**
     * @param $value
     * @return mixed
     */
    public function deserialize($value);
}