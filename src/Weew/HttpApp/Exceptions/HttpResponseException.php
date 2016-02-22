<?php

namespace Weew\HttpApp\Exceptions;

use Exception;
use Weew\Http\IHttpResponse;

class HttpResponseException extends Exception {
    /**
     * @var IHttpResponse
     */
    private $httpResponse;

    /**
     * HttpResponseException constructor.
     *
     * @param IHttpResponse $httResponse
     * @param null $message
     */
    public function __construct(
        IHttpResponse $httResponse,
        $message = null
    ) {
        parent::__construct($message);

        $this->httpResponse = $httResponse;
    }

    /**
     * @return IHttpResponse
     */
    public function getHttpResponse() {
        return $this->httpResponse;
    }
}
