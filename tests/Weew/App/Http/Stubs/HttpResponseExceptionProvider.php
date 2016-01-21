<?php

namespace Tests\Weew\App\Http\Stubs;

use Weew\App\Http\Exceptions\HttpResponseException;
use Weew\Http\HttpResponse;
use Weew\Http\HttpStatusCode;

class HttpResponseExceptionProvider {
    public function boot() {
        throw new HttpResponseException(
            new HttpResponse(HttpStatusCode::OK, 'exception response')
        );
    }
}
