<?php

namespace Weew\App\Http\Events;

use Weew\Eventer\Event;
use Weew\Http\IHttpRequest;
use Weew\Http\IHttpResponse;

class HttpRequestHandledEvent extends Event {
    /**
     * @var IHttpRequest
     */
    private $request;

    /**
     * @var IHttpResponse
     */
    private $response;

    /**
     * HttpRequestHandledEvent constructor.
     *
     * @param IHttpRequest $request
     * @param IHttpResponse $response
     */
    public function __construct(IHttpRequest $request, IHttpResponse $response) {
        $this->request = $request;
        $this->response = $response;
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
}
