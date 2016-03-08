<?php

namespace Weew\HttpApp\Exceptions;

use Exception;
use Weew\Http\IHttpResponse;
use Weew\Http\IHttpResponseable;

class HttpResponseException extends Exception
    implements IHttpResponseable {
    /**
     * @var IHttpResponse
     */
    protected $httpResponse;

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
    public function toHttpResponse() {
        return $this->httpResponse;
    }
}
