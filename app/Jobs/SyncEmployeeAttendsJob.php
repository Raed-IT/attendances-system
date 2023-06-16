<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Rats\Zkteco\Lib\ZKTeco;

class SyncEmployeeAttendsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public User $user;

    /**
     * Create a new job instance.
     */
    public function __construct($data, $user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $zk = new ZKTeco($this->data["device_ip"]);
        if ($zk->connect()) {
            $zk->enableDevice();
            $attendances = $zk->getAttendance();
            foreach ($attendances as $attendance) {
                try {
                    $user_id = $attendance['id'];
                    $data = Arr::except($attendance, ['id']);
                    $data['user_id'] = $user_id;
                    Attendance::updateOrCreate(['timestamp' => $attendance['timestamp'], "uid" => $attendance['uid']], $data);
                } catch (\Exception $e) {
                    info($e);
                    $zk->disableDevice();
                    $notification = Notification::make()->title("فشل مزامنة مزامنة الموظفين" . $attendance["uid"],)->danger();
                    $this->user->notify($notification->toDatabase());
                }
            }
            $zk->disableDevice();
            $zk->disconnect();
            $notification = Notification::make()->title("تم مزامنة  حركة الموظفين")->success();
            $this->user->notify($notification->toDatabase());
        } else {
            Notification::make()->title("لم بتم الوصول الى الجهاز اكد من الاتصال ")->danger()->send();
        }
    }
}
