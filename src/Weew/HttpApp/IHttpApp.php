<?php

namespace Weew\HttpApp;

use Weew\App\IApp;
use Weew\Http\IHttpRequest;
use Weew\Http\IHttpResponse;

interface IHttpApp extends IApp {
    /**
     * @param IHttpRequest $request
     *
     * @return IHttpResponse
     */
    function handleRequest(IHttpRequest $request);

    /**
     * @param IHttpRequest $request
     *
     * @return IHttpResponse
     */
    function handleInternalRequest(IHttpRequest $request);
}
