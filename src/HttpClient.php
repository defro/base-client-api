<?php
namespace fGalvao\BaseClientApi;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{

    const REQUEST_TIMEOUT = 40;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $settings;

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
     * Constructor.
     * ##Settings
     * * BASE_URL (required)
     * * API_HOST (required)
     * * API_KEY (required)
     * * DEV_MODE
     *
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $missing = array_diff(['BASE_URL', 'API_HOST', 'API_KEY'], array_keys($settings));
        if (count($missing)) {
            throw new InvalidArgumentException(sprintf('Missing required setting: %s', implode(',', $missing)));
        }

        $this->settings = $settings;

        $history = Middleware::history($this->apiHistory);

        $handlerStack = HandlerStack::create();
        $handlerStack->push($history);

        $this->client = new Client([
            'verify'      => !$this->settings['DEV_MODE'] ?? true,
            'http_errors' => false,
            'timeout'     => self::REQUEST_TIMEOUT,
            'handler'     => $handlerStack,
            // Base URI is used with relative requests
            'base_uri'    => ltrim($this->settings['BASE_URL'], '/'),
            'headers'     => [
//                'Content-Type'    => 'application/json',
'Accept'          => 'application/json',
'x-rapidapi-host' => $this->settings['API_HOST'],
'x-rapidapi-key'  => $this->settings['API_KEY'],
            ],
        ]);

    }


    /**
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
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