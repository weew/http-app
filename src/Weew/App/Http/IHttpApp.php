<?php

namespace Weew\App\Http;

use Weew\Http\IHttpRequest;
use Weew\Http\IHttpResponse;

interface IHttpApp {
    /**
     * @param IHttpRequest $request
     *
     * @return IHttpResponse
     */
    function handle(IHttpRequest $request);
}
