<?php

namespace RedisAbstract;

use ArrayAccess;
use Countable;
use Iterator;
use IteratorAggregate;

/**
 * A base class for iterable redis entities (lists, hashes, sets and sorted sets)
 * @author Charles Pick
 * @package packages.redis
 */
abstract class IterableEntity extends Entity implements IteratorAggregate, ArrayAccess, Countable
{
    /**
     * The number of items in the entity
     * @var integer
     */
    protected $_count;

    /**
     * Holds the data in the entity
     * @var array
     */
    protected $_data;

    /**
     * Clear internal state
     */
    protected function clearState()
    {
        $this->_count = null;
        $this->_data = null;
    }


    /**
     * Returns an iterator for traversing the items in the set.
     * This method is required by the interface IteratorAggregate.
     * @return Iterator an iterator for traversing the items in the set.
     */
    public function getIterator(): Iterator
    {
        $data = $this->getData();

        return new \ArrayIterator($data);
    }

    /**
     * Returns an iterator for traversing the items in the hash.
     * This method is required by the interface IteratorAggregate.
     * @return \Generator an iterator for traversing the items in the hash.
     */
    public function getGenerator(): \Generator
    {
        $data = $this->getData() ?? [];

        foreach ($data as $k => $v) {
            yield $k => $v;
        }
    }

    /**
     * Returns the number of items in the set.
     * This method is required by Countable interface.
     * @return integer number of items in the set.
     */
    public function count(): int
    {
        return $this->getCount();
    }

    /**
     * Gets a list of items in the set
     * @return array the list of items in array
     */
    public function toArray(): array
    {
        return $this->getData();
    }

    /**
     * Gets the number of items in the entity
     * @return integer the number of items in the entity
     */
    abstract public function getCount():int;

    /**
     * Gets all the members in the entity
     * @param boolean $forceRefresh whether to force a refresh or not
     * @return array the members in the entity
     */
    abstract public function getData(bool $forceRefresh = false): array;

    /**
     * Determines whether the item is contained in the entity
     * @param mixed $item the item to check for
     * @return bool true if the item exists in the entity, otherwise false
     */
    public function contains($item): bool
    {
        return \in_array($item, $this->getData(), false);
    }

    /**
     * Removes all the items from the entity
     * @return IterableEntity the current entity
     */
    public function clear(): IterableEntity
    {
        $this->_data = null;
        $this->_count = null;
        $this->redis->delete($this->name);

        return $this;
    }

}
