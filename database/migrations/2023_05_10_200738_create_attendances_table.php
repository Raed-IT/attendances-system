<?php

use App\Enums\AttendanceStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string("uid")->nullable();
            $table->foreignId("user_id")->nullable()->constrained("employees" ,"userid")->nullOnDelete();
            $table->timestamp("timestamp");
            $table->string("state");
            $table->string("type");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

//// Retrieve all records for a specific employee
//$employeeId = 1;
//$records = DB::table('attendance')->where('employee_id', $employeeId)->orderBy('date')->get();
//
//$totalHours = 0;
//$lastInDate = null;
//
//foreach ($records as $record) {
//    if ($record->type == 'in') {
//        // Store the date and time of the "in" record
//        $lastInDate = Carbon::parse($record->date);
//    } elseif ($record->type == 'out') {
//        // Calculate the difference between the "out" record and the last "in" record
//        $outDate = Carbon::parse($record->date);
//        $hours = $outDate->diffInHours($lastInDate);
//
//        // Add the hours to the total attended by the employee
//        $totalHours += $hours;
//    }
//}
//
//return $totalHours;


