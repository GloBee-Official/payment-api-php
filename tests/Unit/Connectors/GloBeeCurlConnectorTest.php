<?php

namespace Tests\Unit\Connectors;

use GloBee\PaymentApi\Connectors\CurlWrapper;
use GloBee\PaymentApi\Connectors\GloBeeCurlConnector;
use GloBee\PaymentApi\Exceptions\Http\AuthenticationException;
use GloBee\PaymentApi\Exceptions\Http\ForbiddenException;
use GloBee\PaymentApi\Exceptions\Http\HttpException;
use GloBee\PaymentApi\Exceptions\Http\NotFoundException;
use GloBee\PaymentApi\Exceptions\Http\ServerErrorException;
use GloBee\PaymentApi\Exceptions\Validation\ValidationException;
use Mockery\MockInterface;
use Tests\TestCase;

class GloBeeCurlConnectorTest extends TestCase
{
    /**
     * @var MockInterface
     */
    private $wrapperMock;

    /**
     * @var GloBeeCurlConnector
     */
    private $connector;

    public function setUp()
    {
        $this->wrapperMock = \Mockery::mock(CurlWrapper::class);
        $this->connector = new GloBeeCurlConnector('1234', 'https://globee.com', $this->wrapperMock);
    }

    public function test_can_get_data_from_request()
    {
        $this->shouldReceiveSetOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => 'https://globee.com/test',
            CURLOPT_ACCEPT_ENCODING => 'application/json',
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'X-AUTH-KEY: 1234',
            ],
        ])->once();

        $this->wrapperMock->shouldReceive('exec')->andReturn('"OK"')->once();
        $this->wrapperMock->shouldReceive('getInfo')->withArgs([CURLINFO_HTTP_CODE])->andReturn(200);

        $this->assertSame('OK', $this->connector->getJson('test'));
    }

    public function test_can_post_data()
    {
        $this->shouldReceiveSetOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => 'https://globee.com/postTest',
            CURLOPT_ACCEPT_ENCODING => 'application/json',
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                'X-AUTH-KEY: 1234',
                'Content-Type: application/json',
            ],
        ])->once();

        $this->wrapperMock->shouldReceive('setOption')
            ->withArgs([CURLOPT_POSTFIELDS, '{"test":true}'])->once();

        $this->wrapperMock->shouldReceive('exec')->andReturn('"OK"')->once();
        $this->wrapperMock->shouldReceive('getInfo')->withArgs([CURLINFO_HTTP_CODE])->andReturn(200);

        $this->assertSame('OK', $this->connector->postJson('postTest', ['test' => true]));
    }

    public function test_should_throw_authentication_exception_for_401_responses()
    {
        $this->setupMockForResponse(401);

        try {
            $this->connector->getJson('test');

            $this->fail('Expected AuthenticationException to be thrown');
        } catch (AuthenticationException $e) {
            $this->addToAssertionCount(1);
        }
    }

    public function test_should_throw_forbidden_exception_for_403_responses()
    {
        $this->setupMockForResponse(403);

        try {
            $this->connector->getJson('test');

            $this->fail('Expected ForbiddenException to be thrown');
        } catch (ForbiddenException $e) {
            $this->addToAssertionCount(1);
        }
    }

    public function test_should_throw_not_found_exception_for_404_responses()
    {
        $this->setupMockForResponse(404);

        try {
            $this->connector->getJson('test');

            $this->fail('Expected NotFoundException to be thrown');
        } catch (NotFoundException $e) {
            $this->addToAssertionCount(1);
        }
    }

    public function test_should_throw_not_found_exception_for_422_responses()
    {
        $this->setupMockForResponse(422, '{"errors":[{}]}');

        try {
            $this->connector->getJson('test');

            $this->fail('Expected ValidationException to be thrown');
        } catch (ValidationException $e) {
            $this->addToAssertionCount(1);
        }
    }

    public function test_unknown_errors_should_throw_a_general_http_exception()
    {
        $this->setupMockForResponse(418);

        try {
            $this->connector->getJson('test');

            $this->fail('Expected HttpException to be thrown');
        } catch (HttpException $e) {
            $this->addToAssertionCount(1);
        }
    }

    public function test_should_throw_server_error_for_5xx_exceptions()
    {
        $this->setupMockForResponse(500);

        try {
            $this->connector->getJson('test');

            $this->fail('Expected ServerErrorException to be thrown');
        } catch (ServerErrorException $e) {
            $this->addToAssertionCount(1);
        }
    }

    /**
     * @param int    $httpCode
     * @param string $httpBody
     */
    protected function setupMockForResponse($httpCode, $httpBody = '')
    {
        $this->wrapperMock->shouldReceive('setOptions');
        $this->wrapperMock->shouldReceive('exec')->andReturn($httpBody);
        $this->wrapperMock->shouldReceive('getInfo')->withArgs([CURLINFO_HTTP_CODE])->andReturn($httpCode);
    }

    /**
     * @param array $_options
     *
     * @return mixed
     */
    public function shouldReceiveSetOptions(array $_options)
    {
        return $this->wrapperMock->shouldReceive('setOptions')
            ->with(\Mockery::on(function ($options) use ($_options) {
                // Extract Headers
                $headers = $options[CURLOPT_HTTPHEADER];
                $_headers = $_options[CURLOPT_HTTPHEADER];
                unset($options[CURLOPT_HTTPHEADER], $_options[CURLOPT_HTTPHEADER]);

                $diff = array_diff($headers, $_headers);
                $diff += array_diff($options, $_options);

                if (!empty($diff)) {
                    echo 'Array not the same:';
                    print_r($diff);

                    return false;
                }

                return true;
            }));
    }
}
