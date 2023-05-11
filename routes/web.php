<?php

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

use Rats\Zkteco\Lib\ZKTeco;

Route::get('/', function () {
//    $zk = new ZKTeco('192.168.1.201');
//    if ($zk->connect()) {
//        $zk->disableDevice();
////       dd( $zk->removeUser(1501));
//
//    }

    return redirect("/admin");

});
