<?php

namespace Tests\Weew\HttpApp\Stubs;

use Exception;

class RegularExceptionProvider {
    public function boot() {
        throw new Exception('regular exception');
    }
}
