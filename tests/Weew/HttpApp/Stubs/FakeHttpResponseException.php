<?php

namespace Tests\Weew\HttpApp\Stubs;

use Exception;
use Weew\Http\IHttpResponse;
use Weew\Http\IHttpResponseable;

class FakeHttpResponseException extends Exception implements IHttpResponseable {
    private $response;

    public function __construct(IHttpResponse $response, $message = null) {
        parent::__construct($message);
        $this->response = $response;
    }

    public function toHttpResponse() {
        return $this->response;
    }
}
