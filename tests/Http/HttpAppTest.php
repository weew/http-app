<?php

namespace Tests\Weew\App\Http;

use PHPUnit_Framework_TestCase;
use Weew\App\Events\App\AppStartedEvent;
use Weew\App\Events\App\AppShutdownEvent;
use Weew\App\Http\Events\App\HandleHttpRequestEvent;
use Weew\App\Http\Events\App\HttpRequestHandledEvent;
use Weew\App\Http\Events\App\IncomingHttpRequestEvent;
use Weew\App\Http\Exceptions\HttpRequestNotHandled;
use Weew\App\Http\HttpApp;
use Weew\App\Util\EventerTester;
use Weew\Http\HttpRequest;
use Weew\Http\HttpResponse;

class HttpAppTest extends PHPUnit_Framework_TestCase {
    public function test_create() {
        new HttpApp();
    }

    public function test_start_shutdown_handling_and_handled_events() {
        $app = new HttpApp();

        $app->getEventer()->subscribe(HandleHttpRequestEvent::class,
            function(HandleHttpRequestEvent $event) {
                $event->setResponse(new HttpResponse());
             });

        $tester = new EventerTester($app->getEventer());
        $tester->setExpectedEvents([
            AppStartedEvent::class,
            IncomingHttpRequestEvent::class,
            HandleHttpRequestEvent::class,
            HttpRequestHandledEvent::class,
            AppShutdownEvent::class,
        ]);

        $app->handle(new HttpRequest());
        $tester->assert();
    }

    public function test_http_request_not_handled_gets_thrown_without_request_handling_provider() {
        $app = new HttpApp();
        $this->setExpectedException(HttpRequestNotHandled::class);
        $app->handle(new HttpRequest());
    }

    public function test_start_shutdown_handling_and_handled_events_without_request_handling_provider() {
        $app = new HttpApp();

        $tester = new EventerTester($app->getEventer());
        $tester->setExpectedEvents([
            AppStartedEvent::class,
            HandleHttpRequestEvent::class,
            AppShutdownEvent::class,
        ]);

        try {
            $app->handle(new HttpRequest());
        } catch (HttpRequestNotHandled $ex) {}

        $tester->assert();
    }
}
