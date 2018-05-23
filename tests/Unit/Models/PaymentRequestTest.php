<?php

namespace Tests\Unit\Models;

use GloBee\PaymentApi\Exceptions\LockedPropertyException;
use GloBee\PaymentApi\Exceptions\UnknownPropertyException;
use GloBee\PaymentApi\Exceptions\Validation\BelowMinimumException;
use GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException;
use GloBee\PaymentApi\Exceptions\Validation\InvalidEmailException;
use GloBee\PaymentApi\Exceptions\Validation\InvalidSelectionException;
use GloBee\PaymentApi\Exceptions\Validation\InvalidUrlException;
use GloBee\PaymentApi\Exceptions\Validation\ValidationException;
use GloBee\PaymentApi\Models\PaymentRequest;
use Tests\TestCase;

class PaymentRequestTest extends TestCase
{
    /**
     * @var PaymentRequest
     */
    private $paymentRequest;

    public function setUp()
    {
        $this->paymentRequest = new PaymentRequest();
    }

    public function test_getting_an_invalid_property_should_throw_exception()
    {
        try {
            $this->paymentRequest->unknownProperty;
        } catch (UnknownPropertyException $e) {
            $this->addToAssertionCount(1);
            $this->assertEquals('Unknown Property: unknownProperty', $e->getMessage());

            return;
        }

        $this->fail('Expected UnknownPropertyException to be thrown');
    }

    public function test_setting_an_invalid_property_should_throw_exception()
    {
        try {
            $this->paymentRequest->unknownProperty = 123;
        } catch (UnknownPropertyException $e) {
            $this->addToAssertionCount(1);
            $this->assertEquals('Unknown Property: unknownProperty', $e->getMessage());

            return;
        }

        $this->fail('Expected UnknownPropertyException to be thrown');
    }

    public function test_setting_a_locked_property_should_throw_exception()
    {
        try {
            $this->paymentRequest->id = 123;
        } catch (LockedPropertyException $e) {
            $this->addToAssertionCount(1);
            $this->assertEquals('Property "id" is locked and can\'t be modified.', $e->getMessage());

            return;
        }

        $this->fail('Expected LockedPropertyException to be thrown');
    }

    public function test_can_set_valid_data_on_model_using_getters_and_setters()
    {
        $this->paymentRequest->total = 100;
        $this->paymentRequest->currency = 'ABC';
        $this->paymentRequest->customPaymentId = 'test_id';
        $this->paymentRequest->callbackData = 'test_callback_data';
        $this->paymentRequest->customerName = 'Test Name';
        $this->paymentRequest->customerEmail = 'customer@email.com';
        $this->paymentRequest->successUrl = 'https://www.example.com/success';
        $this->paymentRequest->cancelUrl = 'https://www.example.com/cancel';
        $this->paymentRequest->ipnUrl = 'https://www.example.com/ipn';
        $this->paymentRequest->notificationEmail = 'notification@email.com';
        $this->paymentRequest->confirmationSpeed = 'low';

        $this->assertEquals(100, $this->paymentRequest->total);
        $this->assertEquals('ABC', $this->paymentRequest->currency);
        $this->assertEquals('test_id', $this->paymentRequest->customPaymentId);
        $this->assertEquals('test_callback_data', $this->paymentRequest->callbackData);
        $this->assertEquals('Test Name', $this->paymentRequest->customerName);
        $this->assertEquals('customer@email.com', $this->paymentRequest->customerEmail);
        $this->assertEquals('https://www.example.com/success', $this->paymentRequest->successUrl);
        $this->assertEquals('https://www.example.com/cancel', $this->paymentRequest->cancelUrl);
        $this->assertEquals('https://www.example.com/ipn', $this->paymentRequest->ipnUrl);
        $this->assertEquals('notification@email.com', $this->paymentRequest->notificationEmail);
        $this->assertEquals('low', $this->paymentRequest->confirmationSpeed);
    }

    public function test_can_set_valid_data_on_model_using_properties()
    {
        $this->paymentRequest->total = 98765.4321;
        $this->paymentRequest->currency = 'DEF';
        $this->paymentRequest->customPaymentId = 'custom_payment_id';
        $this->paymentRequest->callbackData = 'test callback data';
        $this->paymentRequest->customerName = 'Test Name';
        $this->paymentRequest->customerEmail = 'customer@email.com';
        $this->paymentRequest->successUrl = 'https://www.example.com/success';
        $this->paymentRequest->cancelUrl = 'https://www.example.com/cancel';
        $this->paymentRequest->ipnUrl = 'https://www.example.com/ipn';
        $this->paymentRequest->notificationEmail = 'notification@email.com';
        $this->paymentRequest->confirmationSpeed = 'high';

        $this->assertEquals(98765.4321, $this->paymentRequest->total);
        $this->assertEquals('DEF', $this->paymentRequest->currency);
        $this->assertEquals('custom_payment_id', $this->paymentRequest->customPaymentId);
        $this->assertEquals('test callback data', $this->paymentRequest->callbackData);
        $this->assertEquals('Test Name', $this->paymentRequest->customerName);
        $this->assertEquals('customer@email.com', $this->paymentRequest->customerEmail);
        $this->assertEquals('https://www.example.com/success', $this->paymentRequest->successUrl);
        $this->assertEquals('https://www.example.com/cancel', $this->paymentRequest->cancelUrl);
        $this->assertEquals('https://www.example.com/ipn', $this->paymentRequest->ipnUrl);
        $this->assertEquals('notification@email.com', $this->paymentRequest->notificationEmail);
        $this->assertEquals('high', $this->paymentRequest->confirmationSpeed);
    }

    public function test_sensible_defaults()
    {
        $this->assertSame(0.0, $this->paymentRequest->total);
        $this->assertSame('USD', $this->paymentRequest->currency);
        $this->assertSame('medium', $this->paymentRequest->confirmationSpeed);
        $this->assertNull($this->paymentRequest->customPaymentId);
        $this->assertNull($this->paymentRequest->callbackData);
        $this->assertNull($this->paymentRequest->customerName);
        $this->assertNull($this->paymentRequest->customerEmail);
        $this->assertNull($this->paymentRequest->successUrl);
        $this->assertNull($this->paymentRequest->cancelUrl);
        $this->assertNull($this->paymentRequest->ipnUrl);
        $this->assertNull($this->paymentRequest->notificationEmail);
    }

    public function test_nullable_fields_should_not_throw_an_exception()
    {
        $this->paymentRequest->customPaymentId = null;
        $this->paymentRequest->callbackData = null;
        $this->paymentRequest->customerName = null;
        $this->paymentRequest->successUrl = null;
        $this->paymentRequest->cancelUrl = null;
        $this->paymentRequest->ipnUrl = null;
        $this->paymentRequest->notificationEmail = null;

        $this->assertNull($this->paymentRequest->customPaymentId);
        $this->assertNull($this->paymentRequest->callbackData);
        $this->assertNull($this->paymentRequest->customerName);
        $this->assertNull($this->paymentRequest->successUrl);
        $this->assertNull($this->paymentRequest->cancelUrl);
        $this->assertNull($this->paymentRequest->ipnUrl);
        $this->assertNull($this->paymentRequest->notificationEmail);
    }

    public function test_should_throw_exception_if_total_is_not_more_than_zero()
    {
        try {
            $this->paymentRequest->total = 0;

            $this->fail('Expected BelowMinimumException to be thrown');
        } catch (BelowMinimumException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The total must be at least 0.00', $e->getMessage());
        }
    }

    public function test_should_throw_exception_if_total_is_not_a_number()
    {
        try {
            $this->paymentRequest->total = '';

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected number but found string.', $e->getMessage());
        }
    }

    public function test_currency_should_always_be_uppercase()
    {
        $this->paymentRequest->currency = 'abc';

        $this->assertSame('ABC', $this->paymentRequest->currency);
    }

    public function test_currency_should_throw_exception_if_not_a_string()
    {
        try {
            $this->paymentRequest->currency = [];

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected string but found array.', $e->getMessage());
        }
    }

    public function test_currency_should_throw_exception_if_not_a_3_character_string()
    {
        try {
            $this->paymentRequest->currency = '';

            $this->fail('Expected ValidationException to be thrown');
        } catch (ValidationException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Currency must be a 3 character string.', $e->getMessage());
        }
    }

    public function test_customer_email_should_be_a_valid_email()
    {
        try {
            $this->paymentRequest->customerEmail = [];

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected string but found array.', $e->getMessage());
        }

        try {
            $this->paymentRequest->customerEmail = 'invalid_email';

            $this->fail('Expected InvalidEmailException to be thrown');
        } catch (InvalidEmailException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The customer.email must be a valid email address.', $e->getMessage());
        }
    }

    public function test_notification_email_should_be_a_valid_email()
    {
        try {
            $this->paymentRequest->notificationEmail = [];

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected string but found array.', $e->getMessage());
        }

        try {
            $this->paymentRequest->notificationEmail = 'invalid_email';

            $this->fail('Expected InvalidEmailException to be thrown');
        } catch (InvalidEmailException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The notification_email must be a valid email address.', $e->getMessage());
        }
    }

    public function test_success_url_should_be_a_valid_url()
    {
        try {
            $this->paymentRequest->successUrl = [];

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected string but found array.', $e->getMessage());
        }

        try {
            $this->paymentRequest->successUrl = 'invalid_url';

            $this->fail('Expected InvalidUrlException to be thrown');
        } catch (InvalidUrlException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The success_url format is invalid.', $e->getMessage());
        }
    }

    public function test_cancel_url_should_be_a_valid_url()
    {
        try {
            $this->paymentRequest->cancelUrl = [];

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected string but found array.', $e->getMessage());
        }

        try {
            $this->paymentRequest->cancelUrl = 'invalid_url';

            $this->fail('Expected InvalidUrlException to be thrown');
        } catch (InvalidUrlException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The cancel_url format is invalid.', $e->getMessage());
        }
    }

    public function test_ipn_url_should_be_a_valid_url()
    {
        try {
            $this->paymentRequest->ipnUrl = [];

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected string but found array.', $e->getMessage());
        }

        try {
            $this->paymentRequest->ipnUrl = 'invalid_url';

            $this->fail('Expected InvalidUrlException to be thrown');
        } catch (InvalidUrlException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The ipn_url format is invalid.', $e->getMessage());
        }
    }

    public function test_should_throw_exception_for_invalid_confirmation_speed()
    {
        try {
            $this->paymentRequest->confirmationSpeed = 'invalid_speed';

            $this->fail('Expected InvalidSelectionException to be thrown');
        } catch (InvalidSelectionException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The selected confirmation_speed is invalid.', $e->getMessage());
            $this->assertSame(['low', 'medium', 'high'], $e->getValidOptions());
        }
    }

    public function test_validate_should_pass_for_minimum_required_fields()
    {
        $this->paymentRequest->total = 10;
        $this->paymentRequest->customerEmail = 'customer@email.com';

        $this->assertTrue($this->paymentRequest->isValid());
    }

    public function test_validate_should_fail_if_total_is_not_set()
    {
        $this->paymentRequest->customerEmail = 'customer@email.com';

        $this->assertFalse($this->paymentRequest->isValid());
    }

    public function test_validate_should_fail_if_customer_email_is_not_set()
    {
        $this->paymentRequest->total = 10;

        $this->assertFalse($this->paymentRequest->isValid());
    }

    public function test_can_create_from_response()
    {
        $paymentRequest = PaymentRequest::fromResponse($this->getValidPaymentRequestResponse()['data']);

        $this->assertSame('a1B2c3D4e5F6g7H8i9J0kL', $paymentRequest->id);
        $this->assertSame('unpaid', $paymentRequest->status);
        $this->assertSame('2018-01-25 12:31:04', $paymentRequest->expiresAt);
        $this->assertSame('2018-01-25 12:16:04', $paymentRequest->createdAt);

        $this->assertEquals(123.45, $paymentRequest->total);
        $this->assertEquals('USD', $paymentRequest->currency);
        $this->assertEquals('742', $paymentRequest->customPaymentId);
        $this->assertEquals('example data', $paymentRequest->callbackData);
        $this->assertNull($paymentRequest->notificationEmail);
        $this->assertEquals('high', $paymentRequest->confirmationSpeed);

        $this->assertEquals('John Smit', $paymentRequest->customerName);
        $this->assertEquals('john.smit@hotmail.com', $paymentRequest->customerEmail);
        $this->assertSame('http://www.globee.com/invoice/a1B2c3D4e5F6g7H8i9J0kL', $paymentRequest->redirectUrl);
        $this->assertEquals('https://www.example.com/success', $paymentRequest->successUrl);
        $this->assertEquals('https://www.example.com/cancel', $paymentRequest->cancelUrl);
        $this->assertEquals('https://www.example.com/globee/ipn-callback', $paymentRequest->ipnUrl);
    }

    public function test_can_convert_object_to_array()
    {
        $data = $this->getValidPaymentRequestResponse()['data'];
        $paymentRequest = PaymentRequest::fromResponse($data);

        $this->assertSame($data, $paymentRequest->toArray());
    }

    public function test_callback_data_should_be_stored_as_json()
    {
        $this->paymentRequest->callbackData = ['key' => 'value'];

        $this->assertSame(['key' => 'value'], $this->paymentRequest->callbackData);

        $array = $this->paymentRequest->toArray();
        $this->assertSame('{"key":"value"}', $array['callback_data']);
    }

    public function test_if_callback_data_is_json_it_should_be_decoded_from_response()
    {
        $data = $this->getValidPaymentRequestResponse()['data'];
        $data['callback_data'] = json_encode(['key' => 'value']);
        $paymentRequest = PaymentRequest::fromResponse($data);

        $this->assertSame(['key' => 'value'], $paymentRequest->callbackData);
    }

    public function test_exists_return_false_on_new_payment_request()
    {
        $this->assertFalse($this->paymentRequest->exists());
    }

    public function test_exists_return_true_for_payment_request_from_response()
    {
        $paymentRequest = PaymentRequest::fromResponse($this->getValidPaymentRequestResponse()['data']);

        $this->assertTrue($paymentRequest->exists());
    }
}
