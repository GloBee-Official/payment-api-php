<?php

namespace GloBee\PaymentApi;

use GloBee\PaymentApi\Connectors\Connector;
use GloBee\PaymentApi\Exceptions\PaymentRequestAlreadyExistsException;
use GloBee\PaymentApi\Models\PaymentRequest;

class PaymentApi
{
    /**
     * @var Connector
     */
    private $connector;

    /**
     * PaymentApi constructor.
     *
     * @param Connector $connector
     */
    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * @param string $paymentRequestId
     *
     * @return PaymentRequest
     */
    public function getPaymentRequest($paymentRequestId)
    {
        $response = $this->connector->getJson('v1/payment-request/'.$paymentRequestId);

        return PaymentRequest::fromResponse($response['data']);
    }

    /**
     * @param PaymentRequest $paymentRequest
     *
     * @return PaymentRequest
     * @throws PaymentRequestAlreadyExistsException
     */
    public function createPaymentRequest(PaymentRequest $paymentRequest)
    {
        if ($paymentRequest->exists()) {
            throw new PaymentRequestAlreadyExistsException();
        }
        $data = $this->filterData($paymentRequest->toArray());
        $response = $this->connector->postJson('v1/payment-request', $data);

        return PaymentRequest::fromResponse($response['data']);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function filterData(array $data)
    {
        $data = array_map(function ($item) {
            if (is_array($item)) {
                $item = $this->filterData($item);
            }

            return $item;
        }, $data);

        return array_filter($data, function ($item) {
            if (is_array($item)) {
                return !empty($item);
            }

            return $item !== null;
        });
    }
}
