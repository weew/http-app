<?php

namespace Weew\App\Http;

use Weew\App\App;
use Weew\App\Http\Events\HandleHttpRequestEvent;
use Weew\App\Http\Events\HttpRequestHandledEvent;
use Weew\App\Http\Events\IncomingHttpRequestEvent;
use Weew\App\Http\Exceptions\HttpRequestNotHandled;
use Weew\Http\HttpRequest;
use Weew\Http\IHttpRequest;
use Weew\Http\IHttpResponse;

class HttpApp extends App implements IHttpApp {
    /**
     * @param IHttpRequest $request
     *
     * @return IHttpResponse
     * @throws HttpRequestNotHandled
     */
    public function handle(IHttpRequest $request) {
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

        // shutdown application
        $this->shutdown();

        // in case there was a valid http response,
        // return it as the final application result
        if ($response instanceof IHttpResponse) {
            return $response;
        }

        // if there was no one to handle the http request,
        // throw an exception to show that something is wrong
        throw new HttpRequestNotHandled(s(
            'Http request was not handled. ' .
            'It seems like no one is registered to handle it. ' .
            'You can solve this by handling the "%s" event and providing ' .
            'a valid IHttpResponse i.e: $event->setResponse($response)',
            HandleHttpRequestEvent::class
        ));
    }
}
