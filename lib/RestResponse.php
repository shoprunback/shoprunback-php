<?php

namespace Shoprunback;

use Shoprunback\Error\Error;
use Shoprunback\Error\ReferenceTaken;
use Shoprunback\Error\UnknownApiToken;
use Shoprunback\Util\Logger;

class RestResponse
{
    private $code;
    private $body;
    private $errors;

    public function __construct($curl)
    {
        $response = json_decode(curl_exec($curl));
        $this->code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($this->success()) {
            $this->body = $response;
        } else {
            $this->errors = $response;
        }
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function success()
    {
        return $this->code >= 200 && $this->code < 300;
    }


}