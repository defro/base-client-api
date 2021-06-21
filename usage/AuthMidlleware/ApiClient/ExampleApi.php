<?php


namespace Example\AuthMiddleware\ApiClient;


use Example\AuthMiddleware\ApiClient\Handler\AuthRequestHandler;
use Example\AuthMiddleware\ApiClient\Requestors\Authentication;
use Example\AuthMiddleware\ApiClient\Requestors\Order;
use fGalvao\BaseClientApi\HttpClient;
use GuzzleHttp\HandlerStack;


/**
 * Class SalesForceApi
 *
 * @package SfApi
 */
class ExampleApi
{
    
    private $authentication;
    private $order;
    
    /**
     * SalesForceApi constructor.
     *
     * @param array $params
     *
     */
    public function __construct(array $params)
    {
        $handlerStack = HandlerStack::create();
        
        $config = [
            'timeout'     => $params['timeout'] ?? 30,
            'verify'      => !($params['isDevEn'] ?? false),
            'base_uri'    => $params['baseUri'],
            'http_errors' => false,
            'headers'     => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ],
            'handler'     => $handlerStack
        ];
        $client = new HttpClient($config);
        
        $this->authentication = new Authentication($client);
        
        $auth = [
            'client_id'     => $params['client_id'],
            'client_secret' => $params['client_secret']
        ];
        
        $handlerStack->push(new AuthRequestHandler($auth, $this->authentication));
        
        $this->order = new Order($client);
    }
    
    
    /**
     * @return Authentication
     */
    public function authentication(): Authentication
    {
        return $this->authentication;
    }
    
    
    /**
     * @return Order
     */
    public function order(): Order
    {
        return $this->order;
    }
    
}