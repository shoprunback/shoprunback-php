<?php

namespace Shoprunback;

use Shoprunback\Error\Error;
use Shoprunback\Error\ReferenceTaken;
use Shoprunback\Error\UnknownApiToken;
use Shoprunback\Util\Logger;
use Shoprunback\RestClient;
use Shoprunback\RestResponse;
use Shoprunback\Util\Inflector;

class RestMocker
{
    public static function request($endpoint, $method = RestClient::GET, $body = [])
    {
        RestClient::verifyMethod($method);

        $action = strtolower($method);
        return self::$action($endpoint, $body);
    }

    private static function get($endpoint, $body)
    {
        $responseBody = self::getBody($endpoint, RestClient::GET);

        $response = new RestResponse();
        $response->setCode(200);
        $response->setBody($responseBody);

        return $response;
    }

    // private static function post($endpoint, $body)
    // {
    //     list($filePath, $fileContent) = self::getFilePathAndContent($endpoint);

    //     if (self::returnKeyOfObjectWithId($fileContent, $idToUpdate)) {
    //         $fileContent[] = $body;

    //         file_put_contents($filePath, $fileContent);

    //         http_response_code(201);
    //         return $body;
    //     }

    //     http_response_code(400);
    //     return false;
    // }

    // private static function put($endpoint, $body)
    // {
    //     list($filePath, $fileContent) = self::getFilePathAndContent($endpoint);

    //     $idToUpdate = self::getIdFromEndpoint($endpoint);
    //     if (!$idToUpdate) {
    //         http_response_code(400);
    //         return false;
    //     }

    //     if ([$key, $resource] = self::returnKeyOfObjectWithId($fileContent, $idToUpdate)) {
    //         foreach ($body as $k => $value) {
    //             $fileContent[$key][$k] = $value;
    //         }

    //         file_put_contents($filePath, $fileContent);

    //         http_response_code(200);
    //         return $fileContent[$key];
    //     }

    //     http_response_code(400);
    //     return false;
    // }

    // private static function delete($endpoint, $body)
    // {
    //     list($filePath, $fileContent) = self::getFilePathAndContent($endpoint);

    //     $idToUpdate = self::getIdFromEndpoint($endpoint);
    //     if (!$idToUpdate) {
    //         http_response_code(400);
    //         return false;
    //     }

    //     if ([$key, $resource] = self::returnKeyOfObjectWithId($fileContent, $idToUpdate)) {
    //         unset($fileContent[$key]);

    //         file_put_contents($filePath, $fileContent);

    //         http_response_code(200);
    //         return '{}';
    //     }

    //     http_response_code(400);
    //     return false;
    // }

    private static function filePath($endpoint, $method)
    {
        $pathParts = explode('/', $endpoint);

        if (count($pathParts) % 2 == 0) {
            $resourceId = $pathParts[count($pathParts) - 1];
            $filename = $pathParts[count($pathParts) - 2];
        } else {
            $filename = $pathParts[count($pathParts) - 1];
        }
        return dirname(__FILE__) . '/data/' . strtolower(Inflector::classify($filename)) . '.json';
    }

    private static function getBody($endpoint, $method, $body = [])
    {
        $filePath = self::filePath($endpoint, $method);

        return json_decode(file_get_contents($filePath,  FILE_USE_INCLUDE_PATH));
    }

    // private static function getIdFromEndpoint($endpoint){
    //     $explodedEndpoint = explode('/', $endpoint);
    //     if (count($explodedEndpoint) <= 1) {
    //         return false;
    //     }

    //     return $explodedEndpoint[1];
    // }

    // private static function returnKeyOfObjectWithId($array, $idToFind)
    // {
    //     if ([$key, $resource] => self::returnKeyAndValueOfObjectWithId($array, $idToFind)) {
    //         return $key;
    //     }

    //     return false;
    // }

    // private static function returnKeyAndValueOfObjectWithId($array, $idToFind)
    // {
    //     foreach ($array as $key => $value) {
    //         if ($value->id == $idToFind) {
    //             return [$key, $value];
    //         }
    //     }

    //     return false;
    // }
}
