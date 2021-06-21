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
    
        $history = Middleware::history($this->apiHistory);
        if (!array_key_exists('handler', $clientConfig)) {
            $handlerStack = HandlerStack::create();
            $handlerStack->push($history);
        
            if (array_key_exists('middleware', $clientConfig)) {
                $middlewares = $clientConfig['middleware'];
                if (is_array($middlewares)) {
                    foreach ($middlewares as $middleware) {
                        $handlerStack->push($middleware);
                    }
                } else {
                    $handlerStack->push($middlewares);
                }
            }
        
            $clientConfig['handler'] = $handlerStack;
        } else {
            $clientConfig['handler']->push($history);
        }
    
        $this->client = new Client($clientConfig);
    }
    
    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
    
    /**
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    protected function call(string $method, string $uri, array $options = []): ResponseInterface
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
    public function get(string $uri, array $params = [], array $options = []): ResponseInterface
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
    public function post(string $uri, array $params = [], array $options = []): ResponseInterface
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
    public function postJson(string $uri, array $params = [], array $options = []): ResponseInterface
    {
        if (!empty($params)) {
            $options['json'] = $params;
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
    public function put(string $uri, array $params = [], array $options = []): ResponseInterface
    {
        if (!empty($params)) {
            $options['form_params'] = $params;
        }
        
        return $this->call('PUT', $uri, $options);
    }
    
    /**
     * @param string $uri
     * @param array  $params
     * @param array  $options
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function putJson(string $uri, array $params = [], array $options = []): ResponseInterface
    {
        if (!empty($params)) {
            $options['json'] = $params;
        }
        
        return $this->call('PUT', $uri, $options);
    }
    
    /**
     * @param string $uri
     * @param array  $params
     * @param array  $options
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function patch(string $uri, array $params = [], array $options = []): ResponseInterface
    {
        if (!empty($params)) {
            $options['form_params'] = $params;
        }
        
        return $this->call('PATCH', $uri, $options);
    }
    
    /**
     * @param string $uri
     * @param array  $params
     * @param array  $options
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function patchJson(string $uri, array $params = [], array $options = []): ResponseInterface
    {
        if (!empty($params)) {
            $options['json'] = $params;
        }
        
        return $this->call('PATCH', $uri, $options);
    }
}