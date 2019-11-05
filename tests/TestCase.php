<?php

namespace Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * @return array
     */
    protected function getValidPaymentRequestResponse()
    {
        return [
            'success' => true,
            'data' => [
                'id' => 'a1B2c3D4e5F6g7H8i9J0kL',
                'status' => 'unpaid',
                'total' => '123.45',
                'currency' => 'USD',
                'custom_payment_id' => '742',
                'callback_data' => 'example data',
                'customer' => [
                    'name' => 'John Smit',
                    'email' => 'john.smit@hotmail.com',
                ],
                'redirect_url' => 'http://www.globee.com/invoice/a1B2c3D4e5F6g7H8i9J0kL',
                'success_url' => 'https://www.example.com/success',
                'cancel_url' => 'https://www.example.com/cancel',
                'ipn_url' => 'https://www.example.com/globee/ipn-callback',
                'notification_email' => null,
                'confirmation_speed' => 'high',
                'expires_at' => '2018-01-25 12:31:04',
                'created_at' => '2018-01-25 12:16:04',
            ],
        ];
    }
}
