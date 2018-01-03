<?php

namespace RedisAbstract;


/**
 * Represents a persistent hash stored in redis.
 * <pre>
 * $hash = new RedisAbstract\HashTable("myHash");
 * $hash['a key'] = "some value"; // value is instantly saved to redis
 * $hash['another key'] = "some other value"; // value is instantly saved to redis
 * </pre>
 */
class HashTable extends IterableEntity
{
    use SerializebleTrait;

    /**
     * Set an item to the hash
     * @param string $key the hash key
     * @param mixed $value the item to add
     * @return bool true if the item was added, otherwise false
     */
    public function set(string $key, $value): bool
    {
        if ($this->redis->hSet($this->name, $key, $this->serialize($value)) === false) {
            return false;
        }
        $this->clearState();

        return true;
    }

    /**
     * Adds a item to the hash stored at key only if this field isn't already in the hash.
     * @param string $key the hash key
     * @param mixed $value the item to add
     * @return bool true if the item was added, otherwise false
     */

    public function setNx(string $key, $value): bool
    {
        if ($this->redis->hSetNx($this->name, $key, $this->serialize($value)) === false) {
            return false;
        }
        $this->clearState();

        return true;
    }

    /**
     * @param $key
     * @param int $byAmount
     * @return int
     */
    public function increment(string $key, $byAmount = 1): int
    {
        $this->clearState();

        return $this->redis->hIncrBy($this->name, $key, $byAmount);
    }

    /**
     * @param string $key
     * @param int $byAmount
     * @return string
     */
    public function incrementByFloat(string $key, $byAmount): string
    {
        $this->clearState();

        return $this->redis->hIncrByFloat($this->name, $key, $byAmount);
    }


    /**
     * Removes an item from the hash
     * @param string $key the hash key to remove
     * @return bool true if the item was removed, otherwise false
     */
    public function remove(string $key): bool
    {
        if (!$this->redis->hDel($this->name, $key)) {
            return false;
        }
        $this->clearState();

        return true;
    }

    /**
     * Get an item in the hash
     * @param string $key the hash key to remove
     * @return mixed
     */
    public function get(string $key)
    {
        $this->clearState();

        return $this->deserialize($this->redis->hGet($this->name, $key));
    }


    /**
     * Gets the number of items in the hash
     * @param bool $forceRefresh
     * @return int the number of items in the set
     */
    public function getCount(bool $forceRefresh = false): int
    {
        if ($forceRefresh) {
            $this->clearState();
        }

        if ($this->_count === null) {
            $this->_count = $this->redis->hLen($this->name);
        }

        return (int)$this->_count;
    }

    /**
     * Gets all the members in the hash
     * @param boolean $forceRefresh whether to force a refresh or not
     * @return array the members in the set
     * @throws \Exception
     */
    public function getData(bool $forceRefresh = false): array
    {
        if ($forceRefresh) {
            $this->clearState();
        }

        if ($this->_data === null) {
            $this->pullData();
        }

        return $this->_data ?? [];
    }

    /**
     *
     */
    protected function pullData()
    {
        $this->_data = $this->redis->hGetAll($this->name);
        $this->_count = \count((array)$this->_data);
        if ($this->_data) {
            $this->_data = array_map([$this, 'deserialize'], $this->_data);
        }
    }

    /**
     * Returns whether there is an item at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer $offset the offset to check on
     * @return boolean
     * @throws \Exception
     */
    public function offsetExists($offset)
    {
        return isset($this->getData()[$offset]);
    }

    /**
     * Returns the item at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer $offset the offset to retrieve item.
     * @return mixed the item at the offset
     * @throws \Exception
     */
    public function offsetGet($offset)
    {
        $data = $this->getData();

        return $data[$offset] ?? null;
    }

    /**
     * Sets the item at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer $offset the offset to set item
     * @param mixed $item the item value
     * @throws \Exception
     */
    public function offsetSet($offset, $item)
    {
        $this->set($offset, $item);
    }

    /**
     * Unsets the item at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer $offset the offset to unset item
     * @throws \Exception
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
