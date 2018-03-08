<?php

namespace Shoprunback;

use Shoprunback\Error\Error;
use Shoprunback\Error\ReferenceTaken;
use Shoprunback\Error\UnknownApiToken;
use Shoprunback\Util\Logger;
use Shoprunback\RestResponse;
use Shoprunback\RestMocker;

class RestClient
{
    private $apiBaseUrl;
    private $token;
    private $testing;

    const GET = 'GET';
    const PUT = 'PUT';
    const POST = 'POST';
    const DELETE = 'DELETE';

    private static $_client = null;

    private function __construct()
    {
        $this->setApiBaseUrl(getenv('SHOPRUNBACK_URL'));
        $this->setToken(getenv('SHOPRUNBACK_TOKEN'));
        $this->testing = Shoprunback::isTesting();
    }

    public static function getClient() {
        if(is_null(self::$_client))
        {
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

    public function getApiBaseUrl()
    {
        return $this->apiBaseUrl;
    }

    public function setApiBaseUrl($url)
    {
        $this->apiBaseUrl = $url;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
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

    private function getHeaders() {
        $headers = ['Content-Type: application/json'];
        $headers[] = 'Authorization: Token token=' . $this->getToken();
        return $headers;
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
            CURLOPT_RETURNTRANSFER  => true,
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
            $response = RestMocker::request($endpoint, $method, json_encode($body));
        } else {
            $response = $this->executeQuery($this->getEndpointURL($endpoint), $method, json_encode($body));
        }

        if (!$response->success()) {
            throw new \Exception('Request Error'); #TODO return the RestResponse object (or its errors)
        }

        return $response;
    }

    // public static function get($apiUrlResource, $id = '')
    // {
    //     if ($id) {
    //         return self::request($apiUrlResource . '/' . $id, 'GET');
    //     }

    //     return self::request($apiUrlResource, 'GET');
    // }

    // public static function save($apiUrlResource, $object, $noId = false)
    // {
    //     $json = json_encode($object);

    //     if (isset($object->id)) {
    //         // If the object exists in SRB DB
    //         $getResult = $noId ? Shoprunback::isSetup() : self::get($apiUrlResource, $object->id);
    //         if ($getResult) {
    //             return $noId ? self::request($apiUrlResource, 'PUT', $json) : self::request($apiUrlResource . '/' . $object->id, 'PUT', $json);
    //         }
    //     }

    //     // If no object->id and no getResult
    //     return self::request($apiUrlResource, 'POST', $json);
    // }
}