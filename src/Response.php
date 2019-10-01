<?php

declare(strict_types=1);

namespace Owncloud;

use Psr\Http\Message\StreamInterface;

class Response
{
    /** @var StreamInterface  */
    private $response;

    public function __construct(StreamInterface $response)
    {
        $this->response = $response;
    }

    public function isOk() : bool
    {
        return $this->getStatusCode() === 100;
    }

    public function getStatusCode()
    {
        if (isset($this->response['ocs'])) {
            return $this->response['ocs']['meta']['statuscode'];
        }

        return $this->response['statuscode'];
    }

    public function getMessage()
    {
        if (isset($this->response['ocs'])) {
            return $this->response['ocs']['meta']['message'];
        }

        return $this->response['message'];
    }

    public function getErrorMessage()
    {
        $message = '';
        if ($this->getStatusCode() !== '') {
            $message .= "Code: {$this->getStatusCode()} : ";
        }
        if ($this->getMessage() === '') {
            return 'Not expected response from webservice';
        }

        $message .= $this->getMessage();
        return $message;
    }

    /**
     * Returns the response data.
     *
     * Before returning the data, checks if the response is ok, if it not, the throw Exception.
     */
    public function getData()
    {
        if (! $this->isOk()) {
            throw new ResponseException($this->getErrorMessage());
        }
        return $this->response['ocs']['data'];
    }

    public function getRaw()
    {
        return $this->response;
    }
}
