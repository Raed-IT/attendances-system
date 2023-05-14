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
//    $startTime = '2023-05-14 12:00:00';
//    $endTime = '2023-05-14 14:30:00';
//
//    $diffInHours = \Carbon\Carbon::parse($startTime)->diffInRealMinutes($endTime, )/60;
//    dd($diffInHours);
//    $d=array_values(  \App\Enums\PermanenceTypeEnum::cases() ->toArray());
//    dd($d);
    return redirect("/admin");

});
