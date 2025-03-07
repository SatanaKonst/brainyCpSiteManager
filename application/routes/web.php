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
            $currentUser  = !empty($request->get('currentUser')) ? $request->get('currentUser') : $_ENV['PANEL_LOGIN'];

            if ( !empty($request->get('updateSiteList')) && $request->get('updateSiteList') === 'Y' ) {
                $redis->clerAllData();
            }

            $siteList = $redis->getSiteList($currentUser);
            if ( empty($siteList) ) {
                $siteList = $brainy->getSiteList($currentUser);
                $redis->saveSiteList($currentUser,$siteList);
            }

            /**
             * Получим список запароленых дирректорий
             */
            $passwdDirs = $redis->getAllPasswdDirs($currentUser);
            if ( empty($passwdDirs) ) {
                $passwdDirs = $brainy->getPasswdDir($currentUser);
                $redis->savePasswdDir($currentUser,$passwdDirs);
            }

            if ( !empty($siteList) ) {
                foreach ($siteList as $index => $item) {
                    $siteList[$index] = array(
                        'domain' => $item,
                        'passDirs' => !empty($passwdDirs[$item]) ? $passwdDirs[$item] : [],
                    );
                }
            }
        } catch (Throwable $exception) {
            dump($exception->getMessage());
        }
        return view('welcome', array(
            'sites' => (!empty($siteList)) ? $siteList : [],
            'hostAcc' => $brainy->getHostAccounts(),
            'currentUser' => $currentUser,
        ));
    })->name('home');

    Route::post('/addSite', function (Request $request) {
        $domain = $request->get('domain');
        $dbName = $request->get('dbName');
        $dbPass = $request->get('dbPass');
        $setDirPassword = $request->get('setDirPassword') === 'Y';
        try {
            $brainy = new BrainyApi();
            $fullDbName = $brainy->addSite($domain, $dbName, $dbPass, $setDirPassword);
        } catch (Throwable $exception) {
            dump($exception->getMessage());
            die();
        }
        return redirect(
            route('home', ['addSite=Y', 'fulldbname=' . $fullDbName, 'domain=' . $domain, 'updateSiteList=Y'])
        );
    })->name('addSite');
});

