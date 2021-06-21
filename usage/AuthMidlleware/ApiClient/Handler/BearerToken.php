<?php

namespace Example\AuthMiddleware\ApiClient\Handler;

/**
 * Class BearerToken
 *
 * @package SfApi
 */
class BearerToken
{
    /** @var string $tokenId */
    private $tokenId;
    
    /** @var string $tokenType */
    private $tokenType;
    
    /** @var string $accessToken */
    private $accessToken;
    
    /** @var string $instanceUrl */
    private $instanceUrl;
    
    /** @var int $issuedAt */
    private $issuedAt;
    
    /** @var string $signature */
    private $signature;
    
    /**
     * BearerToken constructor.
     *
     * @param string      $accessToken
     * @param int         $issuedAt
     * @param string      $tokenId
     * @param string      $tokenType
     * @param string      $instanceUrl
     * @param string      $signature
     * @param string|null $refreshToken
     */
    public function __construct(string $accessToken, int $issuedAt, string $tokenId, string $tokenType, string $instanceUrl, string $signature, string $refreshToken = null)
    {
        $this->tokenId      = $tokenId;
        $this->tokenType    = $tokenType;
        $this->accessToken  = $accessToken;
        $this->instanceUrl  = $instanceUrl;
        $this->issuedAt     = $issuedAt;
        $this->signature    = $signature;
        $this->refreshToken = $refreshToken;
    }
    
    
    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->accessToken;
    }
    
    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return true;
    }
    
    /**
     * @return bool
     */
    public function isRefreshable(): bool
    {
        return false;
    }
    
    
}