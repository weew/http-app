<?php

namespace Tests\Weew\HttpApp\Exceptions;

use PHPUnit_Framework_TestCase;
use Weew\HttpApp\Exceptions\HttpResponseException;
use Weew\Http\HttpResponse;

class HttpResponseExceptionTest extends PHPUnit_Framework_TestCase {
    public function test_to_http_response() {
        $response = new HttpResponse();
        $ex = new HttpResponseException($response);
        $this->assertTrue($response === $ex->toHttpResponse());
    }
}
