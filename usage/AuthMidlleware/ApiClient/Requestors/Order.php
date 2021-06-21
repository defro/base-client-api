<?php


namespace Example\AuthMiddleware\ApiClient\Requestors;


use Example\AuthMiddleware\ApiClient\Resources\OrderResource;
use fGalvao\BaseClientApi\Requestor;
use fGalvao\BaseClientApi\Response;

class Order extends Requestor
{
    private const BASE_URI = '/v1.0/Order';
    
    /**
     * @param string $orderId
     *
     * @return OrderResource|null
     */
    public function get(string $orderId): ?OrderResource
    {
        $uri = self::BASE_URI . '/' . $orderId;
        
        $_request = $this->getRequest($uri);
        $request  = $this->toResourceResponse($_request, OrderResource::class);
        
        if ($request->isSuccess()) {
            return $request->getBody();
        }
        
        return null;
    }
    
    /**
     * @param array $params
     *
     * @return Response
     */
    public function create(array $params): Response
    {
        $uri = self::BASE_URI;
        
        $_request = $this->postJsonRequest($uri, $params);
        $request  = $this->toResourceResponse($_request);
        
        return $request;
    }
    
    /**
     * @param string $orderId
     * @param array  $params
     *
     * @return Response
     */
    public function update(string $orderId, array $params): Response
    {
        $uri = self::BASE_URI . '/' . $orderId;
        
        $_request = $this->patchJsonRequest($uri, $params);
        $request  = $this->toResourceResponse($_request);
        
        return $request;
    }
    
}