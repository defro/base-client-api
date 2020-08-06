<?php
namespace fGalvao\BaseClientApi;

use Psr\Http\Message\ResponseInterface;

class Response
{
    /** @var string */
    private $reasonPhrase = '';

    /** @var int */
    private $statusCode = 200;

    /** @var bool */
    private $status = false;

    /** @var mixed */
    private $body = null;

    /** @var mixed */
    private $response = null;

    /**
     * Response constructor.
     *
     * @param ResponseInterface      $response
     * @param ResourceInterface|null $hidrateClass
     */
    public function __construct(ResponseInterface $response, ResourceInterface $hidrateClass = null)
    {
        $this->statusCode   = $response->getStatusCode();
        $this->reasonPhrase = $response->getReasonPhrase();
        $this->status       = ($this->statusCode >= 200 && $this->statusCode < 300);

        $this->body = $this->response = $this->decodeBody($response);
        if ($hidrateClass) {
            $this->body = $hidrateClass::hydrate($this->response);
        }
    }


    /**
     * @param ResponseInterface $response
     *
     * @return string|array
     */
    private function decodeBody(ResponseInterface $response)
    {
        return json_decode($response->getBody(), true) ?: (string)$response->getBody();
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    /**
     * @param bool $status
     *
     * @return Response
     */
    public function setStatus(bool $status): Response
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $body
     *
     * @return Response
     */
    public function setBody($body): Response
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param $response
     *
     * @return $this
     */
    public function setResponse($response): Response
    {
        $this->response = $response;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getBodyData()
    {
        return $this->body['Data'] ?? $this->body;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isFailed()
    {
        return !$this->status;
    }


}