<?php

namespace App\Models;

use Croustibat\FilamentJobsMonitor\Models\QueueMonitor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model ;

class QueueMonitoring extends QueueMonitor
{
    use HasFactory;
    protected $table="queue_monitors";
}
