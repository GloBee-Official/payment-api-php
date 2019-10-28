<?php

namespace Tests\Unit\Exceptions;

use GloBee\PaymentApi\Connectors\CurlWrapper;
use GloBee\PaymentApi\Exceptions\Connectors\ConnectionException;
use GloBee\PaymentApi\Exceptions\Connectors\CurlConnectionException;
use Mockery\MockInterface;
use Tests\TestCase;

class CurlConnectionExceptionTest extends TestCase
{
    /**
     * @var MockInterface|CurlWrapper
     */
    protected $curlWrapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->curlWrapper = \Mockery::mock(CurlWrapper::class);
        $this->curlWrapper->shouldReceive('getErrorMessage')->andReturn('Test Error Message')->once();
        $this->curlWrapper->shouldReceive('getErrorNo')->andReturn(42)->once();
    }

    public function test_can_throw()
    {
        try {
            throw new CurlConnectionException($this->curlWrapper);
        } catch (CurlConnectionException $e) {
            $this->assertSame('Test Error Message', $e->getMessage());
            $this->assertSame(42, $e->getCode());
            $this->assertSame($this->curlWrapper, $e->getCurlConnector());
        }
    }

    public function test_should_extend_connection_exception()
    {
        try {
            throw new CurlConnectionException($this->curlWrapper);
        } catch (ConnectionException $e) {
            $this->addToAssertionCount(1);
        }
    }
}
