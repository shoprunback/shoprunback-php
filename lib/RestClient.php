<?php

namespace Shoprunback;

use Shoprunback\Error\Error;
use Shoprunback\Error\UnknownApiToken;
use Shoprunback\Error\RestClientError;
use Shoprunback\Util\Logger;
use Shoprunback\RestResponse;
use Tests\RestMocker;

class RestClient
{
    private $apiBaseUrl;
    private $token;
    private $testing;
    private $customHeaders = [];

    const GET = 'GET';
    const PUT = 'PUT';
    const POST = 'POST';
    const DELETE = 'DELETE';

    private static $_client = null;

    private function __construct()
    {
        $this->setApiBaseUrl(self::getProductionURL()); // To automatically set the local var if it exists or the production URL
        $this->setToken($this->getToken()); // To automatically set the local var if it exists or the current token used
        $this->testing = Shoprunback::isTesting();
    }

    public static function resetClient()
    {
        self::$_client = new RestClient();
        return self::getClient();
    }

    public static function getClient()
    {
        if (is_null(self::$_client)) {
            self::$_client = new RestClient();
        }

        return self::$_client;
    }

    public function enableTesting()
    {
        $this->testing = true;
    }

    public function disableTesting()
    {
        $this->testing = false;
    }

    public function isTesting()
    {
        return $this->testing;
    }

    public function getApiBaseUrl()
    {
        return $this->apiBaseUrl;
    }

    public function setApiBaseUrl($url)
    {
        // Must not set the local var if testing the functions
        if (getenv('SHOPRUNBACK_URL') && !$this->isTesting()) {
            $this->apiBaseUrl = getenv('SHOPRUNBACK_URL');
        } else {
            $this->apiBaseUrl = $url;
        }
    }

    public function useSandboxEnvironment()
    {
        $this->setApiBaseUrl(self::getSandboxUrl());
    }

    public function useProductionEnvironment()
    {
        $this->setApiBaseUrl(self::getProductionUrl());
    }

    public static function getSandboxUrl()
    {
        return 'https://sandbox.dashboard.shoprunback.com';
    }

    public static function getProductionUrl()
    {
        return 'https://dashboard.shoprunback.com';
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        // Must not set the local var if testing the functions
        if (getenv('SHOPRUNBACK_TOKEN') && !$this->isTesting()) {
            $this->token = getenv('SHOPRUNBACK_TOKEN');
        } else {
            $this->token = $token;
        }
    }

    public function getCustomHeaders()
    {
        return $this->customHeaders;
    }

    public function setCustomHeaders($customHeaders)
    {
        if (is_array($customHeaders)) {
            foreach (self::getReservedHeaders() as $header) {
                $countCustomHeaders = count($customHeaders);
                for ($i = 0; $i < $countCustomHeaders; $i++) {
                    if (strpos($customHeaders[$i], $header) !== false) {
                        unset($customHeaders[$i]);
                    }
                }
            }

            $this->customHeaders = $customHeaders;
        }
    }

    public function getApiFullUrl()
    {
        return $this->getApiBaseUrl() . '/api/v1/';
    }

    public function isSetup()
    {
        return $this->getApiBaseUrl() !== null && $this->getToken() !== null;
    }

    public function verifySetup()
    {
        if (!$this->isSetup()) {
            throw new Exception('Missing required credentials');
        }
    }

    private function getEndpointURL($endpoint) {
        return $this->getApiFullUrl() . $endpoint;
    }

    private static function getReservedHeaders()
    {
        return [
            'Content-Type',
            'Authorization',
            'Shoprunback-PHP'
        ];
    }

    private function getBaseHeaders()
    {
        return [
            'Content-Type: application/json',
            'Authorization: Token token=' . $this->getToken(),
            'Shoprunback-PHP: ' . \Shoprunback\Shoprunback::VERSION
        ];
    }

    public function getHeaders()
    {
        return array_merge($this->getCustomHeaders(), $this->getBaseHeaders());
    }

    private static function validMethod($method)
    {
        switch ($method) {
            case 'POST':
            case 'PUT':
            case 'DELETE':
            case 'GET':
                return true;
                break;
            default:
                return false;
        }
    }

    public static function verifyMethod($method)
    {
        if (!self::validMethod($method)) {
            throw new Exception('Incorrect HTTP type');
        }
    }

    private function executeQuery($url, $method, $json)
    {
        self::verifyMethod($method);

        $opts = [
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_HTTPHEADER      => $this->getHeaders(),
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_CONNECTTIMEOUT  => 30,
            CURLOPT_CUSTOMREQUEST   => $method,
            CURLOPT_URL             => $url,
            CURLOPT_POSTFIELDS      => $json
        ];

        $curl = curl_init();
        curl_setopt_array($curl, $opts);
        $response = new RestResponse($curl);
        curl_close($curl);

        return $response;
    }

    public function request($endpoint, $method = self::GET, $body = [])
    {
        $this->verifySetup();

        if ($this->testing) {
            $response = RestMocker::request($endpoint, $method, $body);
        } else {
            $response = $this->executeQuery($this->getEndpointURL($endpoint), $method, json_encode($body));
        }

        if (!$response->success()) {
            throw new RestClientError($response);
        }

        return $response;
    }
}