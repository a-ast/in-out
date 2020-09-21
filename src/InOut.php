<?php

namespace Aa\InOut;

use Aa\InOut\Batch\BatchGenerator;
use Aa\InOut\Batch\GroupingBatchGenerator;
use Aa\InOut\Exception\InOutFailureException;
use Aa\InOut\InOutTask\BatchUploadInterface;
use Aa\InOut\InOutTask\UploadInterface;
use Aa\InOut\Registry\RegistryInterface;
use Aa\InOut\Report\Result\Failure;
use Aa\InOut\Report\Result\ResultInterface;
use Traversable;

class InOut implements InOutInterface
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var int
     */
    private $batchSize = 100;


    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @throws \Aa\InOut\Exception\InOutException
     */
    public function in(string $dataType, iterable $dataProvider)
    {
        $connector = $this->registry->get($dataType);

        if ($connector instanceof BatchUploadInterface) {

            $batches = $this->getDataBatches($dataProvider, $connector->getBatchGroup());
            $this->uploadBatchesAndValidate($connector, $batches);
        }

        if ($connector instanceof UploadInterface) {
            $this->uploadAndValidate($connector, $dataProvider);
        }
    }

    /**
     * @throws InOutFailureException
     */
    private function uploadAndValidate(UploadInterface $connector, iterable $dataProvider)
    {
        $index = 0;

        foreach ($dataProvider as $item) {
            $result = $connector->upload($item);

            $this->processResult($result, $index++);
        }
    }

    /**
     * @throws \Aa\InOut\Exception\InOutFailureException
     */
    private function uploadBatchesAndValidate(BatchUploadInterface $connector, iterable $dataProvider)
    {
        $index = 0;

        foreach ($dataProvider as $batch) {
            $results = $connector->upload($batch);

            foreach ($results as $result) {
                $this->processResult($result, $index);
            }

            $index += $this->batchSize;
        }
    }

    private function getDataBatches(iterable $dataProvider, string $group): Traversable
    {
        $batchGenerator = $this->getBatchGenerator($group);

        return $batchGenerator->getBatches($dataProvider);
    }

    private function getBatchGenerator(string $group)
    {
        $batchSize = $this->batchSize;

        if ('' === $group) {
            return new BatchGenerator($batchSize);
        }

        return new GroupingBatchGenerator($batchSize, $group);
    }

    /**
     * @throws InOutFailureException
     */
    private function processResult(ResultInterface $result, int $index)
    {
        if ($result instanceof Failure) {

            $newIndex = $index + $result->getIndex();
            $failure = $result->withIndex($newIndex);

            throw new InOutFailureException($result->getMessage(), $failure);
        }
    }
}
