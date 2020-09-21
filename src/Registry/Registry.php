<?php

namespace Aa\InOut\Registry;

use Aa\InOut\Exception\UnknownDataTypeException;
use Aa\InOut\InOutTask\InOutTaskInterface;

class Registry implements RegistryInterface
{
    /**
     * @var array|InOutTaskInterface[]
     */
    private $items;

    public function register(string $alias, InOutTaskInterface $task): RegistryInterface
    {
        $this->items[$alias] = $task;

        return $this;
    }

    /**
     * @throws \Aa\InOut\Exception\UnknownDataTypeException
     */
    public function get(string $alias): InOutTaskInterface
    {
        if (false === isset($this->items[$alias])) {
            throw new UnknownDataTypeException(sprintf('Unknown data type: %s', $alias));
        }

        return $this->items[$alias];
    }
}
