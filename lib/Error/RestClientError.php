<?php

namespace Shoprunback\Error;

class RestClientError extends Error
{
    public function __construct($response)
    {
        $this->response = $response;
    }
}