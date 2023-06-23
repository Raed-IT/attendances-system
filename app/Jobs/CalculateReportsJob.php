<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Croustibat\FilamentJobsMonitor\Traits\QueueProgress;

class CalculateReportsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, QueueProgress;

    public User $user;

    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->setProgress(10);
        try {
            $this->setProgress(1);
            $employees = Employee::whereHas("attendances")->whereHas("salary")->with("salary")->get();
            $totalEmp = count($employees);
            $currentEmp = 0;
            info($employees);

            foreach ($employees as $employee) {
                $currentEmp++;
                $this->setProgress(($currentEmp * 100) / $totalEmp);
                $salary = $employee->salary;
                $records = Attendance::where([
                    ["timestamp", '>', now()->firstOfMonth()->format('Y-m-d')],
                    "user_id" => $employee->userid
                ])->orderBy('timestamp')->get();
                // all values  in  minutes
                $shift = $salary->count_of_shift * 60;
                $actual = 0;
                $overTime = 0;
                $faultTime = 0;
                $lastInDate = null;

                $calcLastCheckOut = false;

                foreach ($records as $record) {

                    if ($record->type == 0 && $calcLastCheckOut == false) {
                        $calcLastCheckOut = true;
                        // Store the date and time of the "in" record
                        $lastInDate = Carbon::parse($record->timestamp);

                    } elseif ($record->type == 1 && $calcLastCheckOut == true) {
                        // Calculate the difference between the "out" record and the last "in" record

                        $calcLastCheckOut = false;
                        $outDate = Carbon::parse($record->timestamp);
                        $minutes = $outDate->diffInMinutes($lastInDate);
                        // Add the hours to the total attended by the employee
                        if ($minutes > $shift) {
                            $overTime += $minutes - $shift;
                            $actual += $shift;
                        } else {
                            $faultTime = $shift - $minutes;
                            $actual += $minutes;
                        }

                    }
                }
                Report::updateOrCreate([
                    'moth' => Carbon::now()->startOfMonth()->format("Y-m-d"),
                    "employee_id" => $employee->id,
                ], [
                    "hours" => $actual / 60,
                    "over_time" => $overTime / 60,
                    "fault_time" => $faultTime / 60,
                    "total_time" => (($actual + $overTime) - $faultTime) / 60,
                ]);
            }
            $notification = Notification::make()->title("تم تحليل بيانات الموظفين")->success();
            $this->user->notify($notification->toDatabase());
        } catch (\Exception $e) {
            info($e);
            $notification = Notification::make()->title("فشل تحليل بيانات الموظفين")->danger();
            $this->user->notify($notification->toDatabase());
        }
        $this->setProgress(100);
    }
}
