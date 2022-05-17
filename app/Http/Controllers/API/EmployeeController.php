<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\EmployeeSalary;
use App\Models\Salary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request) {

        $employees_query = Employee::query();

        if ($request->id){
            $employees_query->where('emp_id', $request->id);
        }
        if ($request->email){
            $employees_query->where('email', $request->email);
        }
        if ($request->phone){
            $employees_query->where('phone', $request->phone);
        }
        if ($request->department){
            $employees_query->where('department_id', $request->department);
        }
        if ($request->designation){
            $employees_query->where('designation_id', $request->designation);
        }

        $employees = $employees_query->orderBy('created_at', 'desc')->with(['department', 'designation', 'leaves', 'salaries', 'salary'])->paginate(20);

        salariesHistory($employees);

        return response()->json([
            'status'      =>  true,
            'employees'   =>  $employees
        ], 200);
    }

    public function activeAll(Request $request) {
        $employees_query = Employee::query();

        if ($request->id){
            $employees_query->where('emp_id', $request->id);
        }
        if ($request->department){
            $employees_query->where('department_id', $request->department);
        }
        if ($request->designation){
            $employees_query->where('designation_id', $request->designation);
        }

        $employees = $employees_query->where('status', 1)->orderBy('created_at', 'desc')->with(['department', 'designation', 'leaves', 'salaries', 'salary'])->get();

        salariesHistory($employees);
        return response()->json([
            'status'      =>  true,
            'employees'   =>  $employees
        ], 200);
    }

    public function save(Request $request) {
        $request->validate([
            'employeeId'        =>  'required|unique:employees,emp_id',
            'firstName'         =>  'required',
            'lastName'          =>  'required',
            'email'             =>  'required|unique:employees,email',
            'phone'             =>  'required|unique:employees,phone',
            'address'           =>  'required',
            'state'             =>  'required',
            'zipCode'           =>  'required',
            'country'           =>  'required',
            'salary'            =>  'required',
            'department'        =>  'required',
            'designation'       =>  'required',
            'leaves'             =>  'required',
        ]);

        DB::beginTransaction();
        try {
            $employee = new Employee();
            $employee->emp_id = $request->employeeId;
            $employee->department_id = $request->department;
            $employee->designation_id = $request->designation;
            $employee->fname = $request->firstName;
            $employee->lname = $request->lastName;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->address = $request->address;
            $employee->state = $request->state;
            $employee->zip_code = $request->zipCode;
            $employee->country_id = $request->country;
            $employee->salary_id = $request->salary;
            $employee->save();

            if ($request->leaves && count($request->leaves)){
                $leaves = $request->leaves;
                for ($i = 0; $i < count($leaves); $i++){
                    $employee_leave = new EmployeeLeave();
                    $employee_leave->employee_id = $employee->id;
                    $employee_leave->leave_id = $leaves[$i];
                    $employee_leave->save();
                }
            }

            $employee = Employee::with(['department', 'designation', 'leaves', 'salaries', 'salary'])->find($employee->id);
            salaryHistory($employee);
            DB::commit();
            return response()->json(['status' => true, 'message'=> 'Employee saved successfully', 'employee' => $employee], 201);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function update(Request $request) {

        $request->validate([
            'id'    => [
                'required', function ($attribute, $value, $fail) {
                    $exist = Employee::find($value);
                    if (!$exist) {
                        $fail('Invalid id');
                    }
                },
            ]
        ]);

        $employee = Employee::find($request->id);

        $request->validate([
            'employeeId'        =>  'required|unique:employees,emp_id,'.$employee->id,
            'firstName'         =>  'required',
            'lastName'          =>  'required',
            'email'             =>  'required|unique:employees,email,'.$employee->id,
            'phone'             =>  'required|unique:employees,phone,'.$employee->id,
            'address'           =>  'required',
            'state'             =>  'required',
            'zipCode'           =>  'required',
            'country'           =>  'required',
            'salary'            =>  'required',
            'department'        =>  'required',
            'designation'       =>  'required',
            'leaves'            =>  'required'
        ]);

        DB::beginTransaction();
        try {
            $employee->emp_id = $request->employeeId;
            $employee->department_id = $request->department;
            $employee->designation_id = $request->designation;
            $employee->fname = $request->firstName;
            $employee->lname = $request->lastName;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->address = $request->address;
            $employee->state = $request->state;
            $employee->zip_code = $request->zipCode;
            $employee->country_id = $request->country;
            $employee->salary_id = $request->salary;
            $employee->save();
            DB::commit();

            $employee = Employee::with(['department', 'designation', 'leaves', 'salaries', 'salary'])->find($request->id);
            salaryHistory($employee);
            return response()->json(['status' => true, 'message'=> 'Employee updated successfully', 'employee' => $employee], 200);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function delete(Request $request) {
        $request->validate([
            'id'    => [
                'required', function ($attribute, $value, $fail) {
                    $exist = Employee::find($value);
                    if (!$exist) {
                        $fail('Invalid id');
                    }
                },
            ]
        ]);

        DB::beginTransaction();
        try {
            $leave = Employee::find($request->id);
            $leave->delete();
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Employee deleted successfully'], 200);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function addPaidSalary(Request $request) {

        $request->validate([
            'month'         =>  'required|numeric|gt:0|lte:12',
            'year'          =>  'required|min:4|numeric|gt:0',
            'paidMethod'    =>  'required',
        ]);

        if ($request->paidMethod == 'department-wise'){
            $request->validate([
                'department'    =>  'required',
            ]);
        }

        if ($request->paidMethod == 'designation-wise'){
            $request->validate([
                'designation'    =>  'required',
            ]);
        }

        if ($request->paidMethod == 'individual'){
            $request->validate([
                'employees'  => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $error = 0;
                        for($i = 0; $i < count($value); $i++){
                            $exist = Employee::find($value[$i]);
                            if (!$exist) {
                                $error++;
                            }
                        }
                        if ($error) {
                            $fail('Invalid employee id');
                        }
                    },
                ]
            ]);
        }

        //department
        //designation
        //employees

        DB::beginTransaction();
        try {
            if ($request->paidMethod == 'all-employee'){
                $employee_ids = Employee::where('status', 1)->pluck('id')->toArray();
            }

            if ($request->paidMethod == 'department-wise'){
                $employee_ids = Employee::where('department_id', $request->department)->where('status', 1)->pluck('id')->toArray();
            }

            if ($request->paidMethod == 'designation-wise'){
                $employee_ids = Employee::where('designation_id', $request->designation)->where('status', 1)->pluck('id')->toArray();
            }

            if ($request->paidMethod == 'individual'){
                $employee_ids = $request->employees;
            }

            $already_added = [];
            for ($j = 0; $j < count($employee_ids); $j++) {
                $employee_id = $employee_ids[$j];
                $employee = Employee::find($employee_id);

                $exist_Salary = EmployeeSalary::where('employee_id', $employee_id)->where('month', $request->month)->where('year', $request->year)->with('employee')->first();
                if ($exist_Salary){
                    if ($exist_Salary->status == 0){
                        $employee_salary = $exist_Salary;
                        $employee_salary->employee_id = $employee_id;
                        $employee_salary->month = $request->month;
                        $employee_salary->year = $request->year;
                        $employee_salary->amount = $employee->salary->amount;
                        $employee_salary->status = 1;
                        $employee_salary->save();
                    }else {
                        $already_added[] = $exist_Salary;
                    }
                }else {
                    $employee_salary = new EmployeeSalary();
                    $employee_salary->employee_id = $employee_id;
                    $employee_salary->month = $request->month;
                    $employee_salary->year = $request->year;
                    $employee_salary->amount = $employee->salary->amount;
                    $employee_salary->status = 1;
                    $employee_salary->save();
                }
            }
            DB::commit();
            return response()->json(['status' => true, 'message'=> 'Employee salary added successfully', 'already_added' => $already_added], 201);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }
}
