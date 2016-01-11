<?php

namespace Weew\App\Http\Events\App;

use Weew\Eventer\Event;
use Weew\Http\IHttpRequest;

class IncomingHttpRequestEvent extends Event {
    /**
     * @var IHttpRequest
     */
    private $request;

    /**
     * IncomingHttpRequestEvent constructor.
     *
     * @param IHttpRequest $request
     */
    public function __construct(IHttpRequest $request) {
        $this->request = $request;
    }

    /**
     * @return IHttpRequest
     */
    public function getRequest() {
        return $this->request;
    }
}
