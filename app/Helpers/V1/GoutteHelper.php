<?php

namespace App\Helpers\V1;

use GuzzleHttp\Middleware;

class GoutteHelper {

    public function getNode($request, $selector, $default = null)
    {
        return $request->filter($selector)->count() ? strip_whitespace($request->filter($selector)->first()->text()) : $default;
    }

    public function goutteClient()
    {
        $client = new \Goutte\Client();
        // $client->getClient()->setDefaultOption('config', ['curl' => ['CURLOPT_TIMEOUT' => 2]]);

        return $client;
    }

    public function request(\Goutte\Client $client, $url, $method = 'get')
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        try {
            $request = $client->request(strtoupper($method), $url);
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

        if (($request instanceof \Symfony\Component\DomCrawler\Crawler) === false) {
            return -5;
        }

        // if ($request->getStatusCode() != 200) {
        //     return -3;
        // }

        return $request;
    }

}
