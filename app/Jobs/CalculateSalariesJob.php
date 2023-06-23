<?php

namespace App\Jobs;

use App\Enums\PermanenceTypeEnum;
use App\Models\ActualSalary;
use App\Models\ReportMonth;
use App\Models\Reward;
use App\Models\Rival;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use function PHPUnit\Framework\assertNotEqualsWithDelta;

class  CalculateSalariesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $reports = ReportMonth::all();
        foreach ($reports as $report) {
            $total = 0;
            $acualAward = 0;
            $acualPenalty = 0;
            $discount = 0;
            $additional = 0;
            $employee = $report->employee;
            $salary = $employee->salary;
            $awards = Reward::whereMonth("created_at", Carbon::now()->month)->whereEmployeeId($employee->id)->get();
            $penalties = Rival::whereMonth("created_at", Carbon::now()->month)->whereEmployeeId($employee->id)->get();
            foreach ($awards as $award) {
                $acualAward += $award->val;
            }

            foreach ($penalties as $penalty) {
                $acualPenalty += $penalty->val;
            }
            if ($employee->salary->type == PermanenceTypeEnum::CONSTANT->value) {
                $total = $employee->salary->price;
            } else {
                $shifts = $report->hours / $employee->salary->count_of_shift;
                $total = $shifts * $employee->salary->price;
            }
            ActualSalary::updateOrCreate(
                [
                    "employee_id" => $employee->id,
                    "created_at" => Carbon::now()->firstOfMonth()->format("Y-m-d")
                ], [
                "total" => $total,
                'report_id' => $report->id,
                "award" => $acualAward,
                "penalty" => $acualPenalty,
                "employee_id" => $report->employee_id,
                "discount" => 0,
                "additional" => 0,
            ]);
        }
    }
}
