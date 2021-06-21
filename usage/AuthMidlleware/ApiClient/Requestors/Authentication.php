<?php

namespace Example\AuthMiddleware\ApiClient\Requestors;


use fGalvao\BaseClientApi\Requestor;
use fGalvao\BaseClientApi\Response;
use function GuzzleHttp\choose_handler;

/**
 * Class Authentication
 *
 * Based on this article
 * https://medium.com/@brad_brothers/build-a-better-api-client-with-guzzle-middleware-2ace56868dc7
 *
 * @package SfApi\Requestors
 */
class Authentication extends Requestor
{
    
    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $username
     * @param string $password
     *
     * @return Response
     */
    public function getToken(string $clientId, string $clientSecret)
    {
        $uri = 'services/oauth2/token';
        
        $params = [
            'grant_type'    => 'password',
            'client_id'     => $clientId,
            'client_secret' => $clientSecret
        ];
        
        $options = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'handler' => choose_handler(),
        ];
        
        $_response = $this->postRequest($uri, $params, $options);
        $response  = $this->toResourceResponse($_response);
        
        if ($response->isSuccess()) {
            return $response->getBody();
        }
        
        return null;
    }
    
    
    public function refreshToken($refreshToken)
    {
        $uri = 'services/oauth2/token';
        
        $params = [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];
        
        $options = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'handler' => choose_handler(),
        ];
        
        $_response = $this->postRequest($uri, $params, $options);
        $response  = $this->toResourceResponse($_response);
        
        return $response;
    }
    
}