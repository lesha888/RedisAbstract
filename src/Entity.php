<?php

namespace RedisAbstract;

use Redis;
use RedisAbstract\Exception\InvalidArgument;

abstract class Entity
{
    /**
     * The name of the redis entity (key)
     * @var string
     */
    protected $name;

    /**
     * Holds the redis connection
     * @var Redis
     */
    protected $redis;

    /**
     * Constructor
     * @param string $name the name of the entity
     * @param Redis $redis the redis connection to use with this entity
     * @throws InvalidArgument
     */
    public function __construct(string $name, Redis $redis = null)
    {
        if ($name === '') {
            throw new InvalidArgument('Name is empty');
        }
        $this->name = $name;
        if ($redis instanceof Redis) {
            $this->setRedis($redis);
        }
    }

    /**
     * Sets the expiration time in seconds to this entity
     * @param integer number of expiration for this entity in seconds
     * @return bool
     * @throws \Exception
     */
    public function expire(int $seconds): bool
    {
        return $this->redis->expire($this->name, $seconds);
    }

    /**
     * Remove the existing timeout on key
     * @return bool
     */
    public function persist(): bool
    {
        return $this->redis->persist($this->name);
    }

    /**
     * Verify if the specified key exists
     * @return bool
     */
    public function exists(): bool
    {
        return $this->redis->exists($this->name);
    }


    /**
     * @return int
     */
    public function delete(): int
    {
        return $this->redis->delete($this->name);
    }

    /**
     * @param Redis $redis
     * @return $this
     */
    public function setRedis(Redis $redis)
    {
        $this->redis = $redis;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


}