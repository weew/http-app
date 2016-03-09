<?php

namespace Tests\Weew\HttpApp\Stubs;

use Weew\Http\HttpResponse;
use Weew\Http\HttpStatusCode;

class HttpResponseExceptionProvider {
    public function boot() {
        throw new FakeHttpResponseException(
            new HttpResponse(HttpStatusCode::OK, 'exception response')
        );
    }
}
