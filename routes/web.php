<?php

use App\Enums\AttendanceTypeEnum;
use App\Enums\EmployeeDeviceRoleEnum;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Rats\Zkteco\Lib\ZKTeco;
use Spatie\Period\Period;
use Spatie\Period\Precision;

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
    //2023-06-01 00:00:00
    dd(Carbon::parse("2023-06-01 00:00:00"));
    //    $records = Attendance::where([
//        ["timestamp", '>', now()->firstOfMonth()->format('Y-m-d')],
//        "user_id" => 1190
//    ])->orderBy('timestamp')->pluck("timestamp");
//    $employees = \App\Models\Employee::doesnthave("attendances")->get();
//    dd($employees);
//    foreach ($employees as $employee){
//        $employee->attendances()->delete();
//        $employee->delete();
//    }

//    dd(AttendanceTypeEnum::());
//    $zk = new ZKTeco('192.168.1.211');
//    if ($zk->connect()) {
//        $zk->enableDevice();
//        dd($zk->getAttendance());
//        $zk->disableDevice();
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
