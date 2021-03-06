<?php

namespace Tests\Weew\HttpApp;

use Exception;
use PHPUnit_Framework_TestCase;
use Tests\Weew\HttpApp\Util\EventerTester;
use Tests\Weew\HttpApp\Stubs\HttpResponseExceptionProvider;
use Tests\Weew\HttpApp\Stubs\RegularExceptionProvider;
use Weew\App\Events\AppShutdownEvent;
use Weew\App\Events\AppStartedEvent;
use Weew\HttpApp\Events\HandleHttpRequestEvent;
use Weew\HttpApp\Events\HttpRequestHandledEvent;
use Weew\HttpApp\Events\IncomingHttpRequestEvent;
use Weew\HttpApp\HttpApp;
use Weew\Http\HttpRequest;
use Weew\Http\HttpResponse;
use Weew\Http\HttpStatusCode;
use Weew\Http\IHttpResponse;
use Weew\HttpApp\IHttpApp;

class HttpAppTest extends PHPUnit_Framework_TestCase {
    public function test_create() {
        new HttpApp();
    }

    public function test_shares_its_instance_in_the_container() {
        $app = new HttpApp();
        $this->assertTrue($app->getContainer()->get(HttpApp::class) === $app);
        $this->assertTrue($app->getContainer()->get(IHttpApp::class) === $app);
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

        $app->handleRequest(new HttpRequest());
        $tester->assert();
    }

    public function test_handle_internal() {
        $app = new HttpApp();

        $app->getEventer()->subscribe(HandleHttpRequestEvent::class,
            function(HandleHttpRequestEvent $event) {
                $event->setResponse(new HttpResponse());
            });

        $this->assertTrue(
            $app->handleInternalRequest(new HttpRequest()) instanceof IHttpResponse
        );
        $this->assertTrue(
            $app->handleInternalRequest(new HttpRequest()) instanceof IHttpResponse
        );
        $this->assertTrue(
            $app->handleInternalRequest(new HttpRequest()) instanceof IHttpResponse
        );
    }

    public function test_http_request_not_handled_results_in_404() {
        $app = new HttpApp();
        $response = $app->handleRequest(new HttpRequest());
        $this->assertTrue($response instanceof IHttpResponse);
        $this->assertEquals(HttpStatusCode::NOT_FOUND, $response->getStatusCode());
    }

    public function test_app_handles_http_responseable_exceptions() {
        $app = new HttpApp();
        $app->getKernel()->addProvider(HttpResponseExceptionProvider::class);
        $response = $app->handleRequest(new HttpRequest());
        $this->assertEquals('exception response', $response->getContent());
    }

    public function test_app_rethrows_exceptions_that_are_not_http_responseable() {
        $app = new HttpApp();
        $app->getKernel()->addProvider(RegularExceptionProvider::class);
        $this->setExpectedException(Exception::class, 'regular exception');
        $response = $app->handleRequest(new HttpRequest());
    }

    public function test_switch_env_in_env_aware_mode() {
        $app = new HttpApp();
        $request = new HttpRequest();

        $app->handleRequest($request);
        $this->assertEquals('dev', $app->getEnvironment());

        $request = new HttpRequest();
        $app->setDebug(false);
        $request->getHeaders()->set('x-env', 'prod');
        $app->handleRequest($request);
        $this->assertEquals('dev', $app->getEnvironment());
        $this->assertNull($request->getHeaders()->find('x-env'));

        $request = new HttpRequest();
        $request->getHeaders()->set('x-env', 'prod');
        $app->setDebug(true);
        $app->handleRequest($request);
        $this->assertEquals('prod', $app->getEnvironment());
        $this->assertNull($request->getHeaders()->find('x-env'));

        $request = new HttpRequest();
        $request->getUrl()->getQuery()->set('env', 'stage');
        $app->setDebug(true);
        $app->handleRequest($request);
        $this->assertEquals('stage', $app->getEnvironment());
        $this->assertNull($request->getUrl()->getQuery()->get('env'));

        $request = new HttpRequest();
        $request->getUrl()->setPath('/env=test/some/url');
        $app->setDebug(true);
        $app->handleRequest($request);
        $this->assertEquals('test', $app->getEnvironment());
        $this->assertEquals('/some/url', $request->getUrl()->getPath());

        $request = new HttpRequest();
        $request->getUrl()->setPath('/some/url/env=demo');
        $app->setDebug(true);
        $app->handleRequest($request);
        $this->assertEquals('demo', $app->getEnvironment());
        $this->assertEquals('/some/url', $request->getUrl()->getPath());

        $request = new HttpRequest();
        $request->getUrl()->setPath('/some/env=stage/url');
        $app->setDebug(true);
        $app->handleRequest($request);
        $this->assertEquals('stage', $app->getEnvironment());
        $this->assertEquals('/some/url', $request->getUrl()->getPath());
    }

    /**
     * @runInSeparateProcess
     */
    public function test_shutdown_with_response() {
        $app = new HttpApp();
        $app->shutdownWithResponse(new HttpResponse());
    }
}
