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

    public function test_getting_an_invalid_property_should_throw_exception()
    {
        $paymentRequest = new PaymentRequest(1, 'test@example.com');
        try {
            $paymentRequest->unknownProperty;
        } catch (UnknownPropertyException $e) {
            $this->addToAssertionCount(1);
            $this->assertEquals('Unknown Property: unknownProperty', $e->getMessage());

            return;
        }

        $this->fail('Expected UnknownPropertyException to be thrown');
    }

    public function test_can_set_valid_data_on_model_using_getters_and_setters()
    {
        $paymentRequest = PaymentRequest::create(100, 'ABC', 'customer@email.com', 'Test Name')
            ->withCustomPaymentId('test_id')
            ->withCallbackData('test_callback_data')
            ->withSuccessUrl('https://www.example.com/success')
            ->withCancelUrl('https://www.example.com/cancel')
            ->withIpnUrl('https://www.example.com/ipn')
            ->withNotificationEmail('notification@email.com')
            ->lowRiskConfirmation();

        $this->assertEquals(100, $paymentRequest->total);
        $this->assertEquals('ABC', $paymentRequest->currency);
        $this->assertEquals('test_id', $paymentRequest->customPaymentId);
        $this->assertEquals('test_callback_data', $paymentRequest->callbackData);
        $this->assertEquals('Test Name', $paymentRequest->customerName);
        $this->assertEquals('customer@email.com', $paymentRequest->customerEmail);
        $this->assertEquals('https://www.example.com/success', $paymentRequest->successUrl);
        $this->assertEquals('https://www.example.com/cancel', $paymentRequest->cancelUrl);
        $this->assertEquals('https://www.example.com/ipn', $paymentRequest->ipnUrl);
        $this->assertEquals('notification@email.com', $paymentRequest->notificationEmail);
        $this->assertEquals('low', $paymentRequest->confirmationSpeed);
    }

    public function test_can_set_valid_data_on_model_using_properties()
    {
        $paymentRequest = PaymentRequest::create(98765.4321, 'DEF', 'customer@email.com', 'Test Name')
            ->withCustomPaymentId('custom_payment_id')
            ->withCallbackData('test callback data')
            ->withSuccessUrl('https://www.example.com/success')
            ->withCancelUrl('https://www.example.com/cancel')
            ->withIpnUrl('https://www.example.com/ipn')
            ->withNotificationEmail('notification@email.com')
            ->quickConfirmation();

        $this->assertEquals(98765.4321, $paymentRequest->total);
        $this->assertEquals('DEF', $paymentRequest->currency);
        $this->assertEquals('custom_payment_id', $paymentRequest->customPaymentId);
        $this->assertEquals('test callback data', $paymentRequest->callbackData);
        $this->assertEquals('Test Name', $paymentRequest->customerName);
        $this->assertEquals('customer@email.com', $paymentRequest->customerEmail);
        $this->assertEquals('https://www.example.com/success', $paymentRequest->successUrl);
        $this->assertEquals('https://www.example.com/cancel', $paymentRequest->cancelUrl);
        $this->assertEquals('https://www.example.com/ipn', $paymentRequest->ipnUrl);
        $this->assertEquals('notification@email.com', $paymentRequest->notificationEmail);
        $this->assertEquals('high', $paymentRequest->confirmationSpeed);
    }

    public function test_sensible_defaults()
    {
        $paymentRequest = new PaymentRequest(1, 'client@example.com');
        $this->assertSame('USD', $paymentRequest->currency);
        $this->assertSame('medium', $paymentRequest->confirmationSpeed);
        $this->assertNull($paymentRequest->customPaymentId);
        $this->assertNull($paymentRequest->callbackData);
        $this->assertNull($paymentRequest->customerName);
        $this->assertNull($paymentRequest->successUrl);
        $this->assertNull($paymentRequest->cancelUrl);
        $this->assertNull($paymentRequest->ipnUrl);
        $this->assertNull($paymentRequest->notificationEmail);
    }

    public function test_should_throw_exception_if_total_is_not_more_than_zero()
    {
        try {
            new PaymentRequest(0, 'client@example.com');

            $this->fail('Expected BelowMinimumException to be thrown');
        } catch (BelowMinimumException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The total must be at least 0.00', $e->getMessage());
        }
    }

    public function test_should_throw_exception_if_total_is_not_a_number()
    {
        try {
            new PaymentRequest('', 'client@example.com');

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected number but found string.', $e->getMessage());
        }
    }

    public function test_currency_should_always_be_uppercase()
    {
        $paymentRequest = PaymentRequest::create(1, 'abc', 'client@example.com');

        $this->assertSame('ABC', $paymentRequest->currency);
    }

    public function test_currency_should_throw_exception_if_not_a_string()
    {
        try {
            $paymentRequest = PaymentRequest::create(1, [], 'client@example.com');

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected string but found array.', $e->getMessage());
        }
    }

    public function test_currency_should_throw_exception_if_not_a_3_character_string()
    {
        try {
            $paymentRequest = PaymentRequest::create(1, '', 'client@example.com');

            $this->fail('Expected ValidationException to be thrown');
        } catch (ValidationException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Currency must be a 3 character string.', $e->getMessage());
        }
    }

    public function test_customer_email_should_be_a_valid_email()
    {
        try {
            PaymentRequest::create(1, 'ABC', []);

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected string but found array.', $e->getMessage());
        }

        try {
            PaymentRequest::create(1, 'ABC', 'invalid_email');

            $this->fail('Expected InvalidEmailException to be thrown');
        } catch (InvalidEmailException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The customer.email must be a valid email address.', $e->getMessage());
        }
    }

    public function test_notification_email_should_be_a_valid_email()
    {
        try {
            PaymentRequest::create(1, 'ABC', 'client@example.com')
                ->withNotificationEmail([]);

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected string but found array.', $e->getMessage());
        }

        try {
            PaymentRequest::create(1, 'ABC', 'client@example.com')
                ->withNotificationEmail('invalid_email');

            $this->fail('Expected InvalidEmailException to be thrown');
        } catch (InvalidEmailException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The notification_email must be a valid email address.', $e->getMessage());
        }
    }

    public function test_success_url_should_be_a_valid_url()
    {
        try {
            PaymentRequest::create(1, 'ABC', 'client@example.com')
                ->withSuccessUrl([]);

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected string but found array.', $e->getMessage());
        }

        try {
            PaymentRequest::create(1, 'ABC', 'client@example.com')
                ->withSuccessUrl('invalid_url');

            $this->fail('Expected InvalidUrlException to be thrown');
        } catch (InvalidUrlException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The success_url format is invalid.', $e->getMessage());
        }
    }

    public function test_cancel_url_should_be_a_valid_url()
    {
        try {
            PaymentRequest::create(1, 'ABC', 'client@example.com')
                ->withCancelUrl([]);

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected string but found array.', $e->getMessage());
        }

        try {
            PaymentRequest::create(1, 'ABC', 'client@example.com')
                ->withCancelUrl('invalid_url');

            $this->fail('Expected InvalidUrlException to be thrown');
        } catch (InvalidUrlException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The cancel_url format is invalid.', $e->getMessage());
        }
    }

    public function test_ipn_url_should_be_a_valid_url()
    {
        try {
            PaymentRequest::create(1, 'ABC', 'client@example.com')
                ->withIpnUrl([]);

            $this->fail('Expected InvalidArgumentException to be thrown');
        } catch (InvalidArgumentException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('Expected string but found array.', $e->getMessage());
        }

        try {
            PaymentRequest::create(1, 'ABC', 'client@example.com')
                ->withIpnUrl('invalid_url');

            $this->fail('Expected InvalidUrlException to be thrown');
        } catch (InvalidUrlException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The ipn_url format is invalid.', $e->getMessage());
        }
    }

    public function test_should_throw_exception_for_invalid_confirmation_speed()
    {
        try {
            PaymentRequest::create(1, 'ABC', 'client@example.com')
                ->confirmationSpeed('invalid_speed');

            $this->fail('Expected InvalidSelectionException to be thrown');
        } catch (InvalidSelectionException $e) {
            $this->addToAssertionCount(1);

            $this->assertSame('The selected confirmation_speed is invalid.', $e->getMessage());
            $this->assertSame(['low', 'medium', 'high'], $e->getValidOptions());
        }
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
        $paymentRequest = PaymentRequest::create(1, 'ABC', 'client@example.com')
            ->withCallbackData(['key' => 'value']);

        $this->assertSame(['key' => 'value'], $paymentRequest->callbackData);

        $array = $paymentRequest->toArray();
        $this->assertSame('{"key":"value"}', $array['callback_data']);
    }

    public function test_if_callback_data_is_json_it_should_be_decoded_from_response()
    {
        $data = $this->getValidPaymentRequestResponse()['data'];
        $data['callback_data'] = json_encode(['key' => 'value']);
        $paymentRequest = PaymentRequest::fromResponse($data);

        $this->assertSame(['key' => 'value'], $paymentRequest->callbackData);
    }

    public function test_exists_return_true_for_payment_request_from_response()
    {
        $paymentRequest = PaymentRequest::fromResponse($this->getValidPaymentRequestResponse()['data']);

        $this->assertTrue($paymentRequest->exists());
    }
}
