<?php

namespace Tests\Weew\HttpApp\Events;

use PHPUnit_Framework_TestCase;
use Weew\HttpApp\Events\HttpRequestHandledEvent;
use Weew\Http\HttpRequest;
use Weew\Http\HttpResponse;

class HttpRequestHandledEventTest extends PHPUnit_Framework_TestCase {
    public function test_getters_and_setters() {
        $request = new HttpRequest();
        $response = new HttpResponse();
        $event = new HttpRequestHandledEvent($request, $response);

        $this->assertTrue($event->getRequest() === $request);
        $this->assertTrue($event->getResponse() === $response);
    }
}
