<?php

use App\Http\Controllers\BrainyApi;
use App\Http\Controllers\RedisCashe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/getSites', function (Request $request) {
    if ($request['token'] === $_ENV['MATTERMOST_COMMAND_GET_SITES_TOKEN']) {
        try {
            $redis = new RedisCashe();
            $result = $redis->getSiteList();
            if (empty($result)) {
                $brainy = new BrainyApi();
                $result = $brainy->getSiteList();
                $redis->saveSiteList($result);
            }
            if (is_array($result) && !empty($result)) {
                foreach ($result as $index => $item) {
                    $result[$index] = <<<HTML
[$item](http://$item)
HTML;
                }
            }
        } catch (Throwable $exception) {
            $result = $exception->getMessage();
        }
    } else {
        $result = 'Не соответствующий токен команды';
    }
    return response((is_array($result)) ? implode("\n", $result) : $result);
});
