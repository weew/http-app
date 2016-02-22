<?php

namespace Tests\Weew\HttpApp\Stubs;

use Weew\HttpApp\Exceptions\HttpResponseException;
use Weew\Http\HttpResponse;
use Weew\Http\HttpStatusCode;

class HttpResponseExceptionProvider {
    public function boot() {
        throw new HttpResponseException(
            new HttpResponse(HttpStatusCode::OK, 'exception response')
        );
    }
}
