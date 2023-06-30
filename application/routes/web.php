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
            $brainy = new BrainyApi();

            if (!empty($request->get('updateSiteList')) && $request->get('updateSiteList') === 'Y') {
                $redis->clerAllData();
            }

            $siteList = $redis->getSiteList();
            if (empty($siteList)) {
                $siteList = $brainy->getSiteList();
                $redis->saveSiteList($siteList);
            }

            /**
             * Получим список запароленых дирректорий
             */
            $passwdDirs = $redis->getAllPasswdDirs();
            if (empty($passwdDirs)) {
                $passwdDirs = $brainy->getPasswdDir();
                $redis->savePasswdDir($passwdDirs);
            }

            if (!empty($siteList)) {
                foreach ($siteList as $index => $item) {
                    $siteList[$index] = array(
                        'domain' => $item,
                        'passDirs' => !empty($passwdDirs[$item]) ? $passwdDirs[$item] : []
                    );
                }
            }
        } catch (Throwable $exception) {
            dump($exception->getMessage());
        }
        return view('welcome', array(
            'sites' => (!empty($siteList)) ? $siteList : []
        ));
    });
});

