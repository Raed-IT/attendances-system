<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class);
    }
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
