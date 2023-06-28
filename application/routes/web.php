<?php

use App\Http\Controllers\BrainyApi;
use App\Http\Controllers\RedisCashe;
use App\Http\Controllers\SiteCheckAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::namespace('\App\Http\Controllers')->group(function () {
    Route::get('/', function (Request $request) {
        try {
            $redis = new RedisCashe();
            if (!empty($request->get('updateSiteList')) && $request->get('updateSiteList') === 'Y') {
                $redis->clerAllData();
            }
            $siteList = $redis->getSiteList();
            if (empty($siteList)) {
                $brainy = new BrainyApi();
                $siteList = $brainy->getSiteList();
                $redis->saveSiteList($siteList);
            }

            $testDomain = $siteList[0];
            $testAuthExist = SiteCheckAuth::hasSiteBasikAuth('http://' . $testDomain);
            dump($testAuthExist);
            $redis->saveAuthSitesStatus($testDomain, $testAuthExist);

            dump($redis->getAuthSiteStatus($testDomain));
        } catch (Throwable $exception) {
            dump($exception->getMessage());
        }
        return view('welcome', array(
            'sites' => (!empty($siteList)) ? $siteList : []
        ));
    });

    /**
     * Проверить наличие авторизации на сайте
     */
    Route::get('/checkSiteAuth/{domain}', function ($domain) {
        $result = false;
        if (!empty($domain)) {
            $result = SiteCheckAuth::hasSiteBasikAuth('//' . $domain);
        }
        return response()->json($result);
    })->name('checkSiteAuth');
});

