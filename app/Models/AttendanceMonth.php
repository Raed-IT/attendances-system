<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceMonth extends Attendance
{
    protected $table = "attendances";
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope('timestamp', function (Builder $builder) {
            $builder->whereMonth('timestamp',Carbon::now()->month) ;
        });
        parent::booted(); // TODO: Change the autogenerated stub
    }
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
