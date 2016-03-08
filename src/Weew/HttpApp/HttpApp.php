<?php

namespace Weew\HttpApp;

use Weew\App\App;
use Weew\HttpApp\Events\HandleHttpRequestEvent;
use Weew\HttpApp\Events\HttpRequestHandledEvent;
use Weew\HttpApp\Events\IncomingHttpRequestEvent;
use Weew\HttpApp\Exceptions\HttpResponseException;
use Weew\Http\HttpRequest;
use Weew\Http\HttpResponse;
use Weew\Http\HttpStatusCode;
use Weew\Http\IHttpRequest;
use Weew\Http\IHttpResponse;

class HttpApp extends App implements IHttpApp {
    /**
     * HttpApp constructor.
     */
    public function __construct() {
        parent::__construct();

        $this->container->set([HttpApp::class, IHttpApp::class], $this);
    }

    /**
     * @param IHttpRequest $request
     *
     * @return IHttpResponse
     */
    public function handle(IHttpRequest $request) {
        try {
            return $this->handleRequest($request);
        } catch (HttpResponseException $ex) {
            return $ex->toHttpResponse();
        }
    }

    /**
     * @param IHttpRequest $request
     *
     * @return IHttpResponse
     */
    public function handleInternal(IHttpRequest $request) {
        try {
            return $this->handleRequest($request, true);
        } catch (HttpResponseException $ex) {
            return $ex->toHttpResponse();
        }
    }

    /**
     * @param IHttpRequest $request
     * @param bool $internal
     *
     * @return IHttpResponse
     */
    protected function handleRequest(IHttpRequest $request, $internal = false) {
        // share request instance
        $this->container->set(
            [HttpRequest::class, IHttpRequest::class], $request
        );

        // boot application and the kernel
        $this->start();

        // notify listeners about an incoming http request
        $this->eventer->dispatch(new IncomingHttpRequestEvent($request));

        // allow listeners to handle the request and provide a valid http response
        $event = new HandleHttpRequestEvent($request);
        $this->eventer->dispatch($event);
        $response = $event->getResponse();

        // in case there is a valid http response
        if ($response instanceof IHttpResponse) {
            // give listeners a chance to modify it
            $this->eventer->dispatch(
                new HttpRequestHandledEvent($request, $response)
            );
        }

        // do not shutdown app on an internal requests
        if ( ! $internal) {
            // shutdown application
            $this->shutdown();
        }

        // in case there was a valid http response,
        // return it as the final application result
        if ($response instanceof IHttpResponse) {
            return $response;
        }

        // if there was no one to handle the http request,
        // return a 404 response
        return new HttpResponse(
            HttpStatusCode::NOT_FOUND,
            s(
                'Http request was not handled. ' .
                'It seems like no one is registered to handle it. ' .
                'You can solve this by handling the "%s" event and providing ' .
                'a valid IHttpResponse i.e: $event->setResponse($response)',
                HandleHttpRequestEvent::class
            )
        );
    }
}
