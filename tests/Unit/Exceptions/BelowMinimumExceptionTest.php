<?php

namespace Tests\Unit\Exceptions;

use GloBee\PaymentApi\Exceptions\Validation\BelowMinimumException;
use GloBee\PaymentApi\Exceptions\Validation\ValidationException;
use Tests\TestCase;

class BelowMinimumExceptionTest extends TestCase
{
    public function test_can_throw()
    {
        try {
            throw new BelowMinimumException('total', 5, 10);
        } catch (BelowMinimumException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The total must be at least 10.00', $e->getMessage());
            $this->assertSame(10, $e->getMinimum());
        }
    }

    public function test_should_extend_validation_exception()
    {
        try {
            throw new BelowMinimumException('test', 5, 10);
        } catch (ValidationException $e) {
            $this->addToAssertionCount(1);
        }
    }
}
