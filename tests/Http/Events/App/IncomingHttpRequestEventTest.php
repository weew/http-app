<?php

namespace Tests\Weew\App\Http\Events\App;

use PHPUnit_Framework_TestCase;
use Weew\App\Http\Events\App\IncomingHttpRequestEvent;
use Weew\Http\HttpRequest;

class IncomingHttpRequestEventTest extends PHPUnit_Framework_TestCase {
    public function test_getters_and_setters() {
        $request = new HttpRequest();
        $event = new IncomingHttpRequestEvent($request);
        $this->assertTrue($event->getRequest() === $request);
    }
}
