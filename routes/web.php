<?php

use App\Enums\AttendanceTypeEnum;
use App\Enums\EmployeeDeviceRoleEnum;
use Carbon\Carbon;
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


Route::get('/', function () {
//    dd(AttendanceTypeEnum::());
//    $zk = new ZKTeco('192.168.1.211');
//    if ($zk->connect()) {
//        $zk->disableDevice();
//       dd( $zk->getUser());
//
//    }
    dd(Carbon::now()->startOfMonth()->format("d-m-Y"));

    return redirect("/admin");

});
