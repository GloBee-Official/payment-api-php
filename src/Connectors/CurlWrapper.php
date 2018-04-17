<?php

namespace GloBee\PaymentApi\Connectors;

use GloBee\PaymentApi\Exceptions\Connectors\CurlConnectionException;

/**
 * @codeCoverageIgnore
 */
class CurlWrapper
{
    /**
     * Curl Resource
     *
     * @var resource
     */
    protected $client;

    /**
     * @return resource Curl Resource
     */
    protected function getClient()
    {
        if (!is_resource($this->client)) {
            $this->client = curl_init();
        }

        return $this->client;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setOption($key, $value)
    {
        curl_setopt($this->getClient(), $key, $value);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        curl_setopt_array($this->getClient(), $options);
    }

    /**
     * @return mixed
     *
     * @throws \GloBee\PaymentApi\Exceptions\Connectors\CurlConnectionException
     */
    public function exec()
    {
        $result = curl_exec($this->getClient());
        if ($result === false) {
            throw new CurlConnectionException($this);
        }

        return $result;
    }

    /**
     * @param $type
     *
     * @return mixed
     */
    public function getInfo($type)
    {
        return curl_getinfo($this->getClient(), $type);
    }

    /**
     * Returns the cUrl error string
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return curl_error($this->getClient());
    }

    /**
     * Returns the cUrl error number
     *
     * @return int
     */
    public function getErrorNo()
    {
        return curl_errno($this->getClient());
    }

    /**
     * Close the cUrl resource
     */
    public function close()
    {
        curl_close($this->client);
    }
}
