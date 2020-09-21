<?php

namespace Aa\InOut\Report\Result;

class Update implements ResultInterface
{
    /**
     * @var string
     */
    private $dataIdentifier;

    public function __construct(string $dataCode)
    {
        $this->dataIdentifier = $dataCode;
    }

    public function getDataIdentifier(): string
    {
        return $this->dataIdentifier;
    }
}
