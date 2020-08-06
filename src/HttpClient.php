<?php

namespace fGalvao\BaseClientApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{

    const REQUEST_TIMEOUT = 40;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Request
     */
    public $lastRequest;

    /**
     * @var Response
     */
    public $lastResponse;

    /**
     * @var array
     */
    public $apiHistory = [];

    /**
     * HttpClient constructor.
     *
     * @param array $clientConfig
     */
    public function __construct(array $clientConfig)
    {
        if (!array_key_exists('timeout', $clientConfig)) {
            $clientConfig['timeout'] = self::REQUEST_TIMEOUT;
        }

        if (!array_key_exists('handler', $clientConfig)) {
            $history = Middleware::history($this->apiHistory);

            $handlerStack = HandlerStack::create();
            $handlerStack->push($history);

            $clientConfig['handler'] = $handlerStack;
        }

        $this->client = new Client($clientConfig);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    protected function call(string $method, string $uri, array $options = [])
    {
        $response = $this->client->request($method, $uri, $options);

        $last               = end($this->apiHistory);
        $this->lastRequest  = $last['request'];
        $this->lastResponse = $last['response'];

        return $response;
    }

    /**
     * @param string $uri
     * @param array  $params
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function get(string $uri, array $params = [], array $options = [])
    {
        if (!empty($params)) {
            $options['query'] = $params;
        }

        return $this->call('GET', $uri, $options);
    }

    /**
     * @param string $uri
     * @param array  $params
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function post(string $uri, array $params = [], array $options = [])
    {
        if (!empty($params)) {
            $options['form_params'] = $params;
        }

        return $this->call('POST', $uri, $options);
    }

    /**
     * @param string $uri
     * @param array  $params
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function postJson(string $uri, array $params = [], array $options = [])
    {
        if (!empty($params)) {
            $options['json'] = $params;
        }

        return $this->call('POST', $uri, $options);
    }

}