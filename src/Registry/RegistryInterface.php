<?php

namespace Aa\InOut\Registry;

use Aa\InOut\InOutTask\InOutTaskInterface;

interface RegistryInterface
{
    public function register(string $alias, InOutTaskInterface $task);

    /**
     * @throws \Aa\InOut\Exception\UnknownDataTypeException
     */
    public function get(string $alias): InOutTaskInterface;
}
