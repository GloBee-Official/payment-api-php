<?php

namespace Tests\Unit\Models;

use GloBee\PaymentApi\Models\Account;
use Tests\TestCase;

class AccountTest extends TestCase
{
    public function test_can_create_from_response()
    {
        $account = Account::fromResponse([
            'name' => 'TEST NAME',
            'url' => 'https://example.com',
        ]);

        $this->assertSame('TEST NAME', $account->name);
        $this->assertSame('https://example.com', $account->url);
    }
}
