<?php

namespace Shoprunback;

use Shoprunback\Error\Error;
use Shoprunback\Error\ReferenceTaken;
use Shoprunback\Error\UnknownApiToken;
use Shoprunback\Util\Logger;
use Shoprunback\RestClient;
use Shoprunback\RestResponse;

class RestMocker
{
    public static function request($endpoint, $method = RestClient::GET, $body = [])
    {
        if (!$endpoint || !$method) return false;

        if (!in_array($method, [RestClient::GET, RestClient::POST, RestClient::PUT, RestClient::DELETE])) {
            return false;
        }

        $function = strtolower($method);
        return self::$function($endpoint, $body);
    }

    private static function get($endpoint, $body)
    {
        list($filePath, $fileContent) = self::getFilePathAndContent($endpoint);

        $idToUpdate = self::getIdFromEndpoint($endpoint);
        if (!$idToUpdate) {
            http_response_code(200);
            return $fileContent;
        }

        if ([$key, $resource] = self::returnKeyOfObjectWithId($fileContent, $idToUpdate)) {
            http_response_code(200);
            return $resource;
        }

        http_response_code(400);
        return false;
    }

    private static function post($endpoint, $body)
    {
        list($filePath, $fileContent) = self::getFilePathAndContent($endpoint);

        if (self::returnKeyOfObjectWithId($fileContent, $idToUpdate)) {
            $fileContent[] = $body;

            file_put_contents($filePath, $fileContent);

            http_response_code(201);
            return $body;
        }

        http_response_code(400);
        return false;
    }

    private static function put($endpoint, $body)
    {
        list($filePath, $fileContent) = self::getFilePathAndContent($endpoint);

        $idToUpdate = self::getIdFromEndpoint($endpoint);
        if (!$idToUpdate) {
            http_response_code(400);
            return false;
        }

        if ([$key, $resource] = self::returnKeyOfObjectWithId($fileContent, $idToUpdate)) {
            foreach ($body as $k => $value) {
                $fileContent[$key][$k] = $value;
            }

            file_put_contents($filePath, $fileContent);

            http_response_code(200);
            return $fileContent[$key];
        }

        http_response_code(400);
        return false;
    }

    private static function delete($endpoint, $body)
    {
        list($filePath, $fileContent) = self::getFilePathAndContent($endpoint);

        $idToUpdate = self::getIdFromEndpoint($endpoint);
        if (!$idToUpdate) {
            http_response_code(400);
            return false;
        }

        if ([$key, $resource] = self::returnKeyOfObjectWithId($fileContent, $idToUpdate)) {
            unset($fileContent[$key]);

            file_put_contents($filePath, $fileContent);

            http_response_code(200);
            return '{}';
        }

        http_response_code(400);
        return false;
    }

    private static function getFilePathAndContent($fileName)
    {
        return ['data/' . $fileName . '.json', json_decode(file_get_contents('data/' . $fileName . '.json'))];
    }

    private static function getIdFromEndpoint($endpoint){
        $explodedEndpoint = explode('/', $endpoint);
        if (count($explodedEndpoint) <= 1) {
            return false;
        }

        return $explodedEndpoint[1];
    }

    private static function returnKeyOfObjectWithId($array, $idToFind)
    {
        if ([$key, $resource] => self::returnKeyAndValueOfObjectWithId($array, $idToFind)) {
            return $key;
        }

        return false;
    }

    private static function returnKeyAndValueOfObjectWithId($array, $idToFind)
    {
        foreach ($array as $key => $value) {
            if ($value->id == $idToFind) {
                return [$key, $value];
            }
        }

        return false;
    }
}
