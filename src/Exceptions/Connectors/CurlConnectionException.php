<?php

namespace GloBee\PaymentApi\Exceptions\Connectors;

use GloBee\PaymentApi\Connectors\CurlWrapper;

class CurlConnectionException extends ConnectionException
{
    /**
     * @var CurlWrapper
     */
    private $curlConnector;

    public function __construct(CurlWrapper $curlConnector)
    {
        $this->curlConnector = $curlConnector;
        parent::__construct($curlConnector->getErrorMessage(), $curlConnector->getErrorNo());
    }

    /**
     * @return CurlWrapper
     */
    public function getCurlConnector()
    {
        return $this->curlConnector;
    }
}
