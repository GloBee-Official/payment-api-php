<?php

namespace Tests\Unit\Models;

use GloBee\PaymentApi\Models\Account;
use GloBee\PaymentApi\Models\Currency;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    public function test_can_create_from_response()
    {
        $currencies = Currency::fromResponse([
            [
                'id' => 'USD',
                'name' => 'United States Dollar',
            ],
            [
                'id' => 'ZAR',
                'name' => 'South African Rand',
            ],
            [
                'id' => 'BTC',
                'name' => 'Bitcoin',
            ],
            [
                'id' => 'XMR',
                'name' => 'Monero',
            ],
        ]);

        // Account
        $this->assertCount(4, $currencies);

        $currency = array_shift($currencies);
        $this->assertInstanceOf(\GloBee\PaymentApi\Models\Currency::class, $currency);
        $this->assertSame('USD', $currency->id);
        $this->assertSame('United States Dollar', $currency->name);

        $currency = array_shift($currencies);
        $this->assertInstanceOf(\GloBee\PaymentApi\Models\Currency::class, $currency);
        $this->assertSame('ZAR', $currency->id);
        $this->assertSame('South African Rand', $currency->name);
    }
}
