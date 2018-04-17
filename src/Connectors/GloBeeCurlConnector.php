<?php

namespace GloBee\PaymentApi\Connectors;

class GloBeeCurlConnector extends Connector
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var CurlWrapper
     */
    private $client;

    /**
     * GloBeeCurlConnector constructor.
     *
     * @param string           $apiKey
     * @param string           $baseUrl
     * @param CurlWrapper|null $curlConnector
     */
    public function __construct($apiKey, $baseUrl = null, CurlWrapper $curlConnector = null)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl ?: 'https://globee.com/payment-api';
        $this->client = $curlConnector ?: new CurlWrapper();
    }

    /**
     * @param string $uri
     *
     * @return array
     */
    public function getJson($uri)
    {
        return $this->send('GET', $uri);
    }

    /**
     * @param string $uri
     * @param array  $data
     *
     * @return array
     */
    public function postJson($uri, array $data)
    {
        $json = json_encode($data);

        return $this->send('POST', $uri, $json, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param string $body
     * @param array  $headers
     *
     * @return array
     */
    protected function send($method, $uri, $body = null, array $headers = [])
    {
        $headers = $this->compileHeaders($headers);

        $this->client->setOptions([
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $this->baseUrl.'/'.$uri,
            CURLOPT_ACCEPT_ENCODING => 'application/json',
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if ($method !== 'GET' && $body) {
            $this->client->setOption(CURLOPT_POSTFIELDS, $body);
        }

        $response = $this->client->exec();
        $httpcode = $this->client->getInfo(CURLINFO_HTTP_CODE);

        if ($httpcode >= 400) {
            $this->handleErrors($httpcode, $response);
        }

        return json_decode($response, true);
    }

    /**
     * @param array $headers
     *
     * @return array
     */
    protected function compileHeaders(array $headers = [])
    {
        $headers = [
                'X-AUTH-KEY' => $this->apiKey,
            ] + $headers;

        $return = [];
        foreach ($headers as $key => $value) {
            $return[] = $key.': '.$value;
        }

        return $return;
    }
}
