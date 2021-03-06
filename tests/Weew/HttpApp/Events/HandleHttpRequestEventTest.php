<?php

namespace Tests\Weew\HttpApp\Events;

use PHPUnit_Framework_TestCase;
use Weew\HttpApp\Events\HandleHttpRequestEvent;
use Weew\Http\HttpRequest;
use Weew\Http\HttpResponse;

class HandleHttpRequestEventTest extends PHPUnit_Framework_TestCase {
    public function test_getters_and_setters() {
        $request = new HttpRequest();
        $response = new HttpResponse();
        $event = new HandleHttpRequestEvent($request);

        $this->assertTrue($event->getRequest() === $request);
        $this->assertNull($event->getResponse());
        $event->setResponse($response);
        $this->assertTrue($response === $event->getResponse());
    }
}
