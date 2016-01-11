<?php

namespace Weew\App\Http\Events;

use Weew\Eventer\Event;
use Weew\Http\IHttpRequest;
use Weew\Http\IHttpResponse;

class HandleHttpRequestEvent extends Event {
    /**
     * @var IHttpRequest
     */
    private $request;

    /**
     * @var IHttpResponse
     */
    private $response;

    /**
     * HandleHttpRequestEvent constructor.
     *
     * @param IHttpRequest $request
     */
    public function __construct(IHttpRequest $request) {
        $this->request = $request;
    }

    /**
     * @return IHttpRequest
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @return IHttpResponse
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * @param IHttpResponse $response
     */
    public function setResponse(IHttpResponse $response) {
        $this->response = $response;
    }
}
