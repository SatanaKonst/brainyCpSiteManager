<?php

use App\Http\Controllers\BrainyApi;
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

Route::namespace('\App\Http\Controllers')->group(function (){
    Route::get('/', function () {
        try {
            $brainy = new BrainyApi();
            $siteList = $brainy->getSiteList();
        }catch (Throwable $exception){
            dump($exception->getMessage());
        }
        return view('welcome',array(
            'sites' => $siteList
        ));
    });
});

