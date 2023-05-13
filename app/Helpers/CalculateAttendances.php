<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Report;
use Carbon\Carbon;

class CalculateAttendances
{
    static public function calculateHoras()
    {
        $employees = Employee::whereHas("attendances")->get();

        foreach ($employees as $employee) {
            $records = Attendance::whereUserId($employee->userid)->orderBy('timestamp')->get();
            $totalHours = 0;
            $lastInDate = null;
            $calcLastCheckOut = false;

            foreach ($records as $record) {
                if ($record->type == 0) {

                    $calcLastCheckOut = false;

                    // Store the date and time of the "in" record
                    $lastInDate = Carbon::parse($record->timestamp);

                } elseif ($record->type == 1) {
                    // Calculate the difference between the "out" record and the last "in" record
                    if ($calcLastCheckOut == false) {
                        $calcLastCheckOut = true;
                        $outDate = Carbon::parse($record->timestamp);
                        $hours = $outDate->diffInHours($lastInDate);
                        // Add the hours to the total attended by the employee
                        $totalHours += $hours;
                    }
                }
            }
            Report::updateOrCreate([
                'moth' => Carbon::now()->startOfMonth()->format("Y-m-d"),
                "employee_id" => $employee->id,
            ], [
                "hours" => $totalHours,
            ]);
        }


    }
}
