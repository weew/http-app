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
    function handle(IHttpRequest $request);

    /**
     * @param IHttpRequest $request
     *
     * @return IHttpResponse
     */
    function handleInternal(IHttpRequest $request);
}
