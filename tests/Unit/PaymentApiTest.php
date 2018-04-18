<?php

namespace Tests\Unit;

use GloBee\PaymentApi\Connectors\Connector;
use GloBee\PaymentApi\Exceptions\PaymentRequestAlreadyExistsException;
use GloBee\PaymentApi\Models\PaymentRequest;
use GloBee\PaymentApi\PaymentApi;
use Mockery\MockInterface;
use Tests\TestCase;

class PaymentApiTest extends TestCase
{
    /**
     * @var MockInterface
     */
    private $connector;

    /**
     * @var PaymentApi
     */
    private $paymentApi;

    public function setUp()
    {
        $this->connector = \Mockery::mock(Connector::class);
        $this->paymentApi = new PaymentApi($this->connector);
    }

    public function test_can_fetch_payment_request()
    {
        $this->connector->shouldReceive('getJson')
            ->withArgs(['v1/payment-request/a1B2c3D4e5F6g7H8i9J0kL'])
            ->andReturn($this->getValidPaymentRequestResponse())
            ->once();

        $paymentRequest = $this->paymentApi->getPaymentRequest('a1B2c3D4e5F6g7H8i9J0kL');

        $this->assertInstanceOf(PaymentRequest::class, $paymentRequest);
        $this->assertSame('a1B2c3D4e5F6g7H8i9J0kL', $paymentRequest->getId());
    }

    public function test_can_create_new_payment_request()
    {
        $this->connector->shouldReceive('postJson')
            ->withArgs([
                'v1/payment-request',
                [
                    'total' => 123.45,
                    'currency' => 'USD',
                    'customer' => ['email' => 'john.smit@hotmail.com'],
                    'confirmation_speed' => 'medium',
                ],
            ])
            ->andReturn($this->getValidPaymentRequestResponse())
            ->once();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->setTotal(123.45);
        $paymentRequest->setCustomerEmail('john.smit@hotmail.com');

        $response = $this->paymentApi->createPaymentRequest($paymentRequest);

        $this->assertInstanceOf(PaymentRequest::class, $response);
        $this->assertSame('a1B2c3D4e5F6g7H8i9J0kL', $response->getId());
        $this->assertSame('john.smit@hotmail.com', $response->getCustomerEmail());
    }

    public function test_should_throw_exception_if_payment_request_already_exists()
    {
        $connector = \Mockery::mock(Connector::class);

        $connector->shouldNotReceive('post');

        $paymentApi = new PaymentApi($connector);

        $paymentRequest = PaymentRequest::fromResponse($this->getValidPaymentRequestResponse()['data']);

        try {
            $paymentApi->createPaymentRequest($paymentRequest);

            $this->fail('Expected PaymentRequestAlreadyExistsException to be thrown.');
        } catch (PaymentRequestAlreadyExistsException $e) {
            $this->addToAssertionCount(1);
        }
    }
}