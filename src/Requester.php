<?php
namespace fGalvao\BaseClientApi;

use fGalvao\GeoDB\Core\Response as ResourceResponse;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\str;

abstract class Requester
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
    protected function get(string $uri, array $params = [], array $options = [])
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
    protected function post(string $uri, array $params = [], array $options = [])
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
    protected function postJson(string $uri, array $params = [], array $options = [])
    {
        return $this->client->postJson($uri, $params, $options);
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
    protected function lastResponse()
    {
        return $this->client->lastResponse;
    }

    /**
     * @return string
     */
    protected function lastResponseStr()
    {
        return str($this->lastResponse());
    }

    /**
     * @param ResponseInterface      $response
     * @param ResourceInterface|null $hidrateClass
     *
     * @return \fGalvao\GeoDB\Core\Response
     */
    protected function toResourceResponse(ResponseInterface $response, ResourceInterface $hidrateClass = null)
    {
        return new ResourceResponse($response, $hidrateClass);
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