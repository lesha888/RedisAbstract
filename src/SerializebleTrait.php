<?php

namespace RedisAbstract;


use RedisAbstract\Serializer\SerializerInterface;

trait SerializebleTrait
{

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @param $value
     * @return mixed
     */
    public function serialize($value): string
    {
        return $this->serializer ? $this->serializer->serialize($value) : $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function deserialize($value)
    {
        if ($value === false || $value === null) {
            return $value;
        }

        if ($this->serializer === null) {
            return $value;
        }

        return $this->serializer->deserialize($value);
    }

    /**
     * @param SerializerInterface $serializer
     * @return self
     */
    protected function setSerializer(SerializerInterface $serializer): self
    {
        $this->serializer = $serializer;

        return $this;
    }

}