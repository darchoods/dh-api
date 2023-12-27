<?php

namespace App\Helpers\V1;

use GuzzleHttp\Middleware;


class GuzzleHelper {

    public function request($method, $url, $data = [], $client = null)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        if (count($data)) {
            if ($method == 'post') {
                $data = ['form_params' => $data];
            } else {
                $data = ['body' => $data];
            }
        }
        $data['http_errors'] = false;

        // Create a middleware that echoes parts of the request.
        $tapMiddleware = Middleware::tap(function ($request) {
            echo $request->getHeaderLine('Content-Type');
            // application/json
            echo $request->getBody();
            // {"foo":"bar"}
        });

        try {
            if ($client === null || ($client instanceof \GuzzleHttp\Client) === null) {
                $client = new \GuzzleHttp\Client();
            } else {
                $client = new $client;
            }

            //$data['handler'] = $tapMiddleware($client->getConfig('handler'));

            $response = $client->request($method, $url, $data);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getMessage();
            return -1;
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return $e->getMessage();
            return -2;
        } catch (ErrorException $e) {
            return $e->getMessage();
            return -3;
        } catch (\Exception $e) {
            return $e->getMessage();
            return -4;
        }

        //if ($response->getStatusCode() != '200') {
        //    return -5;
        //}

        return $response;
    }

}
