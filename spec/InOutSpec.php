<?php

namespace spec\Aa\InOut;

use Aa\InOut\Registry\Registry;
use Aa\InOut\Registry\RegistryInterface;
use Aa\InOut\InOutTask\InOutTaskInterface;
use Aa\InOut\InOutTask\BatchUploadInterface;
use Aa\InOut\InOutTask\UploadInterface;
use Aa\InOut\Exception\InOutFailureException;
use Aa\InOut\Report\Result\Creation;
use Aa\InOut\Report\Result\Failure;
use ArrayObject;
use PhpSpec\ObjectBehavior;
use Webmozart\Assert\Assert;

class InOutSpec extends ObjectBehavior
{
    function let(InOutTaskInterface $batchTask, InOutTaskInterface $task)
    {
        $batchTask->implement(BatchUploadInterface::class);
        $batchTask->getBatchGroup()->willReturn('');

        $data = [['a' => 1]];

        $batchTask
            ->upload($data)
            ->willReturn(new ArrayObject(new Creation('1')));

        $task->implement(UploadInterface::class);
        $task->upload(['a' => 1])->willReturn(new Creation('1'));
        $task->upload(['b' => 2])->willReturn(new Creation('2'));
        $task->upload(['c' => 3])->willReturn(new Failure('3', 'Error', 123, 0, []));


        $registry = new Registry();
        $registry->register('batch_product', $batchTask->getWrappedObject());
        $registry->register('product', $task->getWrappedObject());


        $this->beConstructedWith($registry);
    }

    function it_loads_data_in_batch()
    {
        $this->in('batch_product', [['a' => 1]]);
    }

    function it_returns_failure_with_correct_index_of_failed_data_item()
    {
        $data = [
            ['a' => 1],
            ['b' => 2],
            ['c' => 3],
        ];

        try {
            $this->in('product', $data);
        } catch (InOutFailureException $exception) {

            Assert::same('Error', $exception->getMessage());
            Assert::same(2,       $exception->getFailure()->getIndex());
            Assert::same('Error', $exception->getFailure()->getMessage());
            Assert::same('3',     $exception->getFailure()->getDataIdentifier());
            Assert::same(123,     $exception->getFailure()->getErrorCode());
            Assert::same([],            $exception->getFailure()->getErrors());
        }
    }
}
