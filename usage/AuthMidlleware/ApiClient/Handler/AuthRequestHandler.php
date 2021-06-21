<?php

namespace Example\AuthMiddleware\ApiClient\Handler;

use Example\AuthMiddleware\ApiClient\Requestors\Authentication;
use GuzzleHttp\Psr7\Request;
use http\Exception\InvalidArgumentException;
use Psr\Http\Message\RequestInterface;


class AuthRequestHandler
{
    /** @var BearerToken */
    private $token;
    
    /** @var Authentication */
    private $authentication;
    
    private $conDetails = [
        'client_id'     => null,
        'client_secret' => null
    ];
    
    /**
     * SfRequestHandler constructor.
     *
     * @param array          $conDetails
     * @param Authentication $authentication
     */
    public function __construct(array $conDetails, Authentication $authentication)
    {
        $this->authentication = $authentication;
        
        $this->conDetails['client_id']     = $conDetails['client_id'] ?? null;
        $this->conDetails['client_secret'] = $conDetails['client_secret'] ?? null;
    }
    
    public function __invoke(callable $next)
    {
        return function (RequestInterface $request, array $options = []) use ($next) {
            $request = $this->applyToken($request);
            return $next($request, $options);
        };
    }
    
    /**
     * @param RequestInterface $request
     *
     * @return Request|RequestInterface
     */
    protected function applyToken(RequestInterface $request)
    {
        if (!$this->hasValidToken()) {
            $this->token = $this->acquireAccessToken();
        }
        
        return $request->withAddedHeader(
            'Authorization',
            'Bearer ' . $this->token->getToken()
        );
        
    }
    
    /**
     * @return bool
     */
    private function hasValidToken(): bool
    {
        return $this->token && $this->token->isValid();
    }
    
    private function acquireAccessToken()
    {
        if (!$this->token->isRefreshable()) {
            $token = $this->authentication->getToken(
                $this->conDetails['client_id'],
                $this->conDetails['client_secret']
            );
        }
        
        if (!$token) {
            throw new InvalidArgumentException('Sales Force Api - Invalid Credentials');
        }
        
        $bearerToken = new BearerToken(
            $token['access_token'],
            (int)$token['issued_at'],
            $token['id'],
            $token['token_type'],
            $token['instance_url'],
            $token['signature']
        );
        
        return $bearerToken;
    }
    
    
}