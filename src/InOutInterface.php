<?php

namespace Aa\InOut;

interface InOutInterface
{
    /**
     * @throws \Aa\InOut\Exception\InOutFailureException
     */
    public function in(string $dataType, iterable $dataProvider);
}
