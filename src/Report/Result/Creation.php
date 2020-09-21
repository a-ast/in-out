<?php

namespace Aa\InOut\Report\Result;

class Creation implements ResultInterface
{
    /**
     * @var string
     */
    private $dataIdentifier;

    public function __construct(string $dataIdentifier)
    {
        $this->dataIdentifier = $dataIdentifier;
    }

    public function getDataIdentifier(): string
    {
        return $this->dataIdentifier;
    }
}
