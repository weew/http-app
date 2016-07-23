<?php

namespace Weew\HttpApp;

use Exception;
use Weew\App\App;
use Weew\Http\IHttpResponseable;
use Weew\HttpApp\Events\HandleHttpRequestEvent;
use Weew\HttpApp\Events\HttpRequestHandledEvent;
use Weew\HttpApp\Events\IncomingHttpRequestEvent;
use Weew\Http\HttpRequest;
use Weew\Http\HttpResponse;
use Weew\Http\HttpStatusCode;
use Weew\Http\IHttpRequest;
use Weew\Http\IHttpResponse;

class HttpApp extends App implements IHttpApp {
    /**
     * HttpApp constructor.
     *
     * @param string $environment
     * @param bool $debug
     */
    public function __construct($environment = null, $debug = null) {
        parent::__construct($environment, $debug);

        $this->container->set([HttpApp::class, IHttpApp::class], $this);
    }

    /**
     * @param IHttpRequest $request
     *
     * @return IHttpResponse
     */
    public function handleRequest(IHttpRequest $request) {
        $this->detectEnvFromRequest($request);

        return $this->handleExceptions(function() use ($request) {
            $response = $this->processRequest($request);
            $this->shutdown();

            return $response;
        });
    }

    /**
     * @param IHttpRequest $request
     *
     * @return IHttpResponse
     */
    public function handleInternalRequest(IHttpRequest $request) {
        return $this->handleExceptions(function() use ($request) {
            return $this->processRequest($request);
        });
    }

    /**
     * @param IHttpResponse $response
     */
    public function shutdownWithResponse(IHttpResponse $response) {
        $this->shutdown();
        $response->send();

        exit;
    }

    /**
     * @param IHttpRequest $request
     *
     * @return IHttpResponse
     */
    protected function processRequest(IHttpRequest $request) {
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

        // in case there was a valid http response,
        // return it as the final application result
        if ( ! $response instanceof IHttpResponse) {
            $response = new HttpResponse(
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

        return $response;
    }

    /**
     * @param callable $callable
     *
     * @return IHttpResponse
     * @throws Exception
     */
    protected function handleExceptions(callable $callable) {
        try {
            return $callable();
        } catch (Exception $ex) {
            if ($ex instanceof IHttpResponseable) {
                return $ex->toHttpResponse();
            }

            throw $ex;
        }
    }

    /**
     * @param IHttpRequest $request
     */
    protected function detectEnvFromRequest(IHttpRequest $request) {
        $envs = [];
        $envs[] = $this->detectEnvFromRequestHeader($request);
        $envs[] = $this->detectEnvFromUrlQuery($request);
        $envs[] = $this->detectEnvFromUrlPath($request);

        if ($this->getDebug()) {
            foreach ($envs as $env) {
                if ($env) {
                    $this->setEnvironment($env);
                    break;
                }
            }
        }
    }

    /**
     * @param IHttpRequest $request
     *
     * @return string
     */
    protected function detectEnvFromRequestHeader(IHttpRequest $request) {
        $env = $request->getHeaders()->find('x-env');
        $request->getHeaders()->remove('x-env');

        return $env;
    }

    /**
     * @param IHttpRequest $request
     *
     * @return mixed
     */
    protected function detectEnvFromUrlQuery(IHttpRequest $request) {
        $env = $request->getUrl()->getQuery()->get('env');

        if (is_scalar($env)) {
            // remove env setting from the url
            $request->getUrl()->getQuery()->remove('env');

            return $env;
        }

        return null;
    }

    /**
     * @param IHttpRequest $request
     *
     * @return mixed
     */
    protected function detectEnvFromUrlPath(IHttpRequest $request) {
        $env = $request->getUrl()->parsePath('/env={env}')->get('env');

        if ($env) {
            // remove environment from the url
            $cleanPath = str_replace(s('/env=%s', $env), '', $request->getUrl()->getPath());
            $request->getUrl()->setPath($cleanPath);
        }

        return $env;
    }
}
