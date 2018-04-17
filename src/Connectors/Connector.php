<?php

namespace GloBee\PaymentApi\Connectors;

use GloBee\PaymentApi\Exceptions\Http\AuthenticationException;
use GloBee\PaymentApi\Exceptions\Http\ForbiddenException;
use GloBee\PaymentApi\Exceptions\Http\HttpException;
use GloBee\PaymentApi\Exceptions\Http\NotFoundException;
use GloBee\PaymentApi\Exceptions\Http\ServerErrorException;
use GloBee\PaymentApi\Exceptions\Validation\ValidationException;

abstract class Connector
{
    /**
     * @param string $uri
     *
     * @return array
     */
    abstract public function getJson($uri);

    /**
     * @param string $uri
     * @param array  $data
     *
     * @return array
     */
    abstract public function postJson($uri, array $data);

    /**
     * @param $code
     * @param $body
     *
     * @throws \GloBee\PaymentApi\Exceptions\Http\HttpException;
     */
    protected function handleErrors($code, $body)
    {
        switch ($code) {
            case 401:
                throw new AuthenticationException();
            case 403:
                throw new ForbiddenException();
            case 404:
                throw new NotFoundException();
            case 422:
                throw new ValidationException(json_decode($body, true)['errors']);
        }

        if ($code >= 500) {
            throw new ServerErrorException();
        }

        throw new HttpException('Unknown HTTP exception', $code);
    }
}
