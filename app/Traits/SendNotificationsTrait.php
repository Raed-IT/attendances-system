<?php

namespace App\Traits;

use App\Models\User;
use Filament\Notifications\Notification;
use function Symfony\Component\String\s;

trait SendNotificationsTrait
{
    public function notifyAdmins($title, $message, $toDataBase = false)
    {
        $notification = Notification::make()->title($title)->body($message ?? '');

        if ($toDataBase) {
            User::whereIsAdmin(true)->get()->each(function ($admin) use ($title, $message, $toDataBase, $notification) {
                $admin->notify($notification->toDatabase());
            });
        } else {
            $notification->send();
        }

    }

    public function notifyCurrentUser($message, $toDataBase = false, bool $success = false)
    {
        $user = auth()->user();

        $notification = Notification::make()->title($message);
        if (!$success) {
            $notification->danger();
        } else {
            $notification->success();
            $notification->send();
        }

        if ($toDataBase) {
            $user->notify($notification->toDatabase());
            \DB::commit();
        }
    }

    public function notifyUser(User $user, $message, $toDataBase = false)
    {
        $notification = Notification::make()->title($message);
        if ($toDataBase) {
            $user->notify($notification->toDatabase());
        } else {
            $notification->send();
        }
    }
}
