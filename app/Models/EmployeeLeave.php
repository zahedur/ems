<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    use HasFactory;

    public function leave() {
        return $this->hasOne(Leave::class, 'id', 'leave_id');
    }

    public function salary() {
        return $this->hasOne(Salary::class, 'id', 'salary_id');
    }
}
