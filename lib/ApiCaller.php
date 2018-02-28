<?php

namespace Shoprunback;

use Shoprunback\Error\Error;
use Shoprunback\Error\ReferenceTaken;
use Shoprunback\Error\UnknownApiToken;
use Shoprunback\Util\Logger;

class ApiCaller
{
    public static function request($endUrl = '', $type = 'GET', $json = '')
    {
        $url = Shoprunback::getApiBaseUrl() . $endUrl;

        $headers = ['Content-Type: application/json'];

        if (Shoprunback::getApiToken()) {
            $headers[] = 'Authorization: Token token=' . Shoprunback::getApiToken();
        }

        $opts = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $type
        ];

        switch ($type) {
            case 'POST':
            case 'PUT':
                if (! $json) {
                    throw new Exception('Trying to do a ' . $type . ' without json');
                }

                if (! is_string($json)) {
                    $json = json_encode($json);
                }

                $opts[CURLOPT_POSTFIELDS] = $json;
                break;
            case 'DELETE':
            case 'GET':
                break;
            default:
                throw new Exception('Incorrect HTTP type');
        }

        $opts[CURLOPT_URL] = $url;

        $curl = curl_init();
        curl_setopt_array($curl, $opts);
        $response = curl_exec($curl);
        $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $response = json_decode($response) ? json_decode($response) : $response;

        return self::handleRequestResponse($response, $httpStatusCode, $json);
    }

    public static function get($apiUrlResource, $id = '')
    {
        if ($id) {
            return self::request($apiUrlResource . '/' . $id, 'GET');
        }

        return self::request($apiUrlResource, 'GET');
    }

    public static function save($apiUrlResource, $object, $noId = false)
    {
        $json = json_encode($object);

        if (isset($object->id)) {
            // If the object exists in SRB DB
            $getResult = $noId ? Shoprunback::isSetup() : self::get($apiUrlResource, $object->id);
            if ($getResult) {
                return $noId ? self::request($apiUrlResource, 'PUT', $json) : self::request($apiUrlResource . '/' . $object->id, 'PUT', $json);
            }
        }

        // If no object->id and no getResult
        return self::request($apiUrlResource, 'POST', $json);
    }

    private static function handleRequestResponse($response, $httpStatusCode, $jsonUsed = '')
    {
        if (is_string($response) && strpos($response, 'Access denied') >= 0) {
            throw new \Shoprunback\Error\UnknownApiToken('No or invalid API Token: "' . Shoprunback::getApiToken() . '"');
        }

        if (isset($response->errors)) {
            foreach ($response->errors as $error) {
                $errorType = '\Shoprunback\Error\\';

                switch ($error) {
                    case 'Reference has already been taken':
                        $errorType .= 'ReferenceTaken';
                        break;
                    default:
                        $errorType .= 'Error';
                        break;
                }

                throw new $errorType($error . '. JSON used: ' . $jsonUsed, $httpStatusCode);
            }
        }

        return $response;
    }
}