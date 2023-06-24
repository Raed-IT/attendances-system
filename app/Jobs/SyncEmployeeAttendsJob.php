<?php

namespace App\Jobs;

use App\Filament\Resources\ErrorSynckResource;
use App\Models\Attendance;
use App\Models\Device;
use App\Models\Employee;
use App\Models\ErrorSyncModel;
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

    public array $data = [];
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
        $deviceId = Device::whereIp($this->data["device_ip"])->first()->id;
        if ($zk->connect()) {
            $zk->enableDevice();
            $attendances = $zk->getAttendance();
            $errors = [];
            foreach ($attendances as $attendance) {
                try {
                    $user_id = $attendance['id'];
                    $data = Arr::except($attendance, ['id']);
                    $data['user_id'] = $user_id;
                    $data["device_id"] = $deviceId;
                    Attendance::updateOrCreate(
                        ['timestamp' => $attendance['timestamp'],
                            "uid" => $attendance['uid'],
                            "device_id" => $deviceId,
                        ]
                        , $data);
                } catch (\Exception $e) {
                    $zk->disableDevice();
                    array_push($errors, $user_id);
                }

            }
            $errorString = "";
            foreach ($errors as $error) {
                $errorString .= $error . ' , ';
            }

            $err = ErrorSyncModel::create([
                "content" => "فشل في مزامنة حركة الحظور والانصراف " . $errorString,
            ]);
            $notificationSuccess = Notification::make()->title("تمت المزامنة")->success();
            $notification = Notification::make()->title("فشل مزامنة   الموظفين")->body("رقم الخطاء في جدوال الاخطاء " . $err->id)->danger();
            $this->user->notify($notificationSuccess->toDatabase());
            $this->user->notify($notification->toDatabase());
            $zk->disableDevice();
            $zk->disconnect();


        } else {
            $notification = Notification::make()->title("لم بتم الوصول الى الجهاز اكد من الاتصال ")->danger();
            $this->user->notify($notification->toDatabase());
        }
    }
}
