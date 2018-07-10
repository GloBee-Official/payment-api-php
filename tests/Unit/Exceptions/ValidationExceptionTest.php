<?php

namespace Tests\Unit\Exceptions;

use GloBee\PaymentApi\Exceptions\Validation\ValidationException;
use Tests\TestCase;

class ValidationExceptionTest extends TestCase
{
    public function test_can_throw()
    {
        try {
            throw new ValidationException(['test_key' => 'test_value']);
        } catch (ValidationException $e) {
            $this->assertSame(['test_key' => 'test_value'], $e->getErrors());
        }
    }
}
