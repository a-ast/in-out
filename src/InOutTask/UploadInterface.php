<?php

namespace Aa\InOut\InOutTask;

use Aa\InOut\Report\Result\ResultInterface;

interface UploadInterface
{
    public function upload(array $data): ResultInterface;
}
