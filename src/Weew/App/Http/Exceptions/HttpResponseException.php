<?php

namespace Weew\App\Http\Exceptions;

use Exception;
use Weew\Http\IHttpResponse;

class HttpResponseException extends Exception {
    /**
     * @var IHttpResponse
     */
    private $response;

    /**
     * HttpResponseException constructor.
     *
     * @param IHttpResponse $response
     */
    public function __construct(IHttpResponse $response) {
        $this->response = $response;
    }

    /**
     * @return IHttpResponse
     */
    public function getResponse() {
        return $this->response;
    }
}
