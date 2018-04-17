<?php

namespace Tests\Unit\Exceptions;

use GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException;
use GloBee\PaymentApi\Exceptions\Validation\ValidationException;
use Tests\TestCase;

class InvalidArgumentExceptionTest extends TestCase
{
    public function test_can_throw()
    {
        try {
            throw new InvalidArgumentException('test', 'string', 10);
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected string but found integer.', $e->getMessage());
            $this->assertSame('string', $e->getExpectedType());
            $this->assertSame('integer', $e->getActualType());
        }
    }

    public function test_should_extend_validation_exception()
    {
        try {
            throw new InvalidArgumentException('test', 'string', 10);
        } catch (ValidationException $e) {
            $this->addToAssertionCount(1);
        }
    }
}
