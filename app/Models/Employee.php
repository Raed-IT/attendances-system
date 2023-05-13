<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = ["section_id"];

    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(AttendanceMonth::class,"user_id","userid");
    }
}
