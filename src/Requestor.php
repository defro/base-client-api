<?php
namespace fGalvao\BaseClientApi;

use fGalvao\BaseClientApi\Response as ResourceResponse;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\str;

abstract class Requestor
{
    /**
     * @var HttpClient
     */
    protected $client;
    
    
    /**
     * Program constructor.
     *
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $uri
     * @param array  $params
     * @param array  $options
     *
     * @return ResponseInterface
     */
    protected function getRequest(string $uri, array $params = [], array $options = []): ResponseInterface
    {
        return $this->client->get($uri, $params, $options);
    }

    /**
     * @param string $uri
     * @param array  $params
     * @param array  $options
     *
     * @return ResponseInterface
     */
    protected function postRequest(string $uri, array $params = [], array $options = []): ResponseInterface
    {
        return $this->client->post($uri, $params, $options);
    }

    /**
     * @param string $uri
     * @param array  $params
     * @param array  $options
     *
     * @return ResponseInterface
     */
    protected function postJsonRequest(string $uri, array $params = [], array $options = []): ResponseInterface
    {
        return $this->client->postJson($uri, $params, $options);
    }
    
    /**
     * @param string $uri
     * @param array  $params
     * @param array  $options
     *
     * @return ResponseInterface
     */
    protected function putRequest(string $uri, array $params = [], array $options = []): ResponseInterface
    {
        return $this->client->put($uri, $params, $options);
    }
    
    /**
     * @param string $uri
     * @param array  $params
     * @param array  $options
     *
     * @return ResponseInterface
     */
    protected function putJsonRequest(string $uri, array $params = [], array $options = []): ResponseInterface
    {
        return $this->client->putJson($uri, $params, $options);
    }
    
    /**
     * @param string $uri
     * @param array  $params
     * @param array  $options
     *
     * @return ResponseInterface
     */
    protected function patchRequest(string $uri, array $params = [], array $options = []): ResponseInterface
    {
        return $this->client->patch($uri, $params, $options);
    }
    
    /**
     * @param string $uri
     * @param array  $params
     * @param array  $options
     *
     * @return ResponseInterface
     */
    protected function patchJsonRequest(string $uri, array $params = [], array $options = []): ResponseInterface
    {
        return $this->client->patchJson($uri, $params, $options);
    }
    
    /**
     * @return array
     */
    protected function getApiCallHistory()
    {
        return $this->client->apiHistory;
    }
    
    /**
     * @return Request
     */
    protected function lastRequest()
    {
        return $this->client->lastRequest;
    }

    /**
     * @return string
     */
    protected function lastRequestStr()
    {
        return str($this->lastRequest());
    }

    /**
     * @return Response
     */
    public function lastResponse()
    {
        return $this->client->lastResponse;
    }

    /**
     * @return string
     */
    public function lastResponseStr()
    {
        return str($this->lastResponse());
    }
    
    /**
     * @param ResponseInterface $response
     * @param string|null       $hydrateClass
     *
     * @return ResourceResponse
     */
    protected function toResourceResponse(ResponseInterface $response, string $hydrateClass = null)
    {
        return new ResourceResponse($response, $hydrateClass);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return string|array
     */
    protected function responseBody(ResponseInterface $response)
    {
        return json_decode($response->getBody(), true) ?: (string)$response->getBody();
    }
}