<?php

namespace Tests\Weew\App\Http;

use PHPUnit_Framework_TestCase;
use Tests\Weew\App\Http\Stubs\HttpResponseExceptionProvider;
use Weew\App\Events\AppShutdownEvent;
use Weew\App\Events\AppStartedEvent;
use Weew\App\Http\Events\HandleHttpRequestEvent;
use Weew\App\Http\Events\HttpRequestHandledEvent;
use Weew\App\Http\Events\IncomingHttpRequestEvent;
use Weew\App\Http\HttpApp;
use Weew\App\Util\EventerTester;
use Weew\Http\HttpRequest;
use Weew\Http\HttpResponse;
use Weew\Http\HttpStatusCode;
use Weew\Http\IHttpResponse;

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

    public function test_http_request_not_handled_results_in_404() {
        $app = new HttpApp();
        $response = $app->handle(new HttpRequest());
        $this->assertTrue($response instanceof IHttpResponse);
        $this->assertEquals(HttpStatusCode::NOT_FOUND, $response->getStatusCode());
    }

    public function test_app_handles_http_response_exceptions() {
        $app = new HttpApp();
        $app->getKernel()->addProvider(HttpResponseExceptionProvider::class);
        $response = $app->handle(new HttpRequest());
        $this->assertEquals('exception response', $response->getContent());
    }
}
