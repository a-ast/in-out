<?php

namespace Aa\InOut\InOutTask;

use Traversable;

interface BatchUploadInterface
{
    /**
     * @return Traversable|\Aa\InOut\Report\Result\ResultInterface[]
     */
    public function upload(array $data): Traversable;

    public function getBatchGroup(): string;
}
