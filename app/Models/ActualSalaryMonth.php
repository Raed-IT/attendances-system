<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActualSalaryMonth extends ActualSalary
{
    use HasFactory;
    protected $table="actual_salaries";
}
