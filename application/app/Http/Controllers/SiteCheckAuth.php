<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SiteCheckAuth extends Controller
{
    /** Проверить сайт на наличие http авторизации
     * @param $url
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function checkSiteBasikAuth($url)
    {
        try {
            $client = new Client();
            $response = $client->get($url);
            return $response->getStatusCode() === 401;
        } catch (\Throwable $exception) {
            return $exception->getCode() === 401;
        }
        return false;
    }
}
