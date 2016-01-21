<?php

namespace Tests\Weew\App\Http\Exceptions;

use PHPUnit_Framework_TestCase;
use Weew\App\Http\Exceptions\HttpResponseException;
use Weew\Http\HttpResponse;

class HttpResponseExceptionTest extends PHPUnit_Framework_TestCase {
    public function test_getters_and_setters() {
        $response = new HttpResponse();
        $ex = new HttpResponseException($response);
        $this->assertTrue($response === $ex->getResponse());
    }
}
