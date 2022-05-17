<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function department() {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function designation() {
        return $this->hasOne(Designation::class, 'id', 'designation_id');
    }

    public function leaves() {
        return $this->hasMany(EmployeeLeave::class, 'employee_id', 'id')->with('leave');
    }

    public function salaries() {
        return $this->hasMany(EmployeeSalary::class, 'employee_id', 'id');
    }

    public function salary() {
        return $this->hasOne(Salary::class, 'id', 'salary_id');
    }
}
