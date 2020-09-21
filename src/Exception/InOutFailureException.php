<?php

namespace Aa\InOut\Exception;

use Aa\InOut\Report\Result\Failure;
use Throwable;

class InOutFailureException extends InOutException
{
    /**
     * @var Failure
     */
    private $failure;

    public function __construct(string $message, Failure $failure, Throwable $previous = null)
    {
        $this->failure = $failure;

        parent::__construct($message, 0, $previous);
    }

    public function getFailure(): Failure
    {
        return $this->failure;
    }
}
