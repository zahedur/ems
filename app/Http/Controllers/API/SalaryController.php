<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Salary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    public function index() {
        $salaries = Salary::orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'status'     =>  true,
            'salaries'   =>  $salaries
        ], 200);
    }

    public function save(Request $request) {
        $request->validate([
            'name'  => 'required',
            'amount'  => 'required|numeric|gt:0'
        ]);

        DB::beginTransaction();
        try {
            $salary = new Salary();
            $salary->name = $request->name;
            $salary->amount = $request->amount;
            $salary->save();
            DB::commit();
            return response()->json(['status' => true, 'message'=> 'Salary saved successfully', 'salary' => $salary], 201);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function update(Request $request) {
        $request->validate([
            'name'    => 'required',
            'amount'  => 'required|numeric|gt:0',
            'id'      => [
                'required', function ($attribute, $value, $fail) {
                    $exist = Salary::find($value);
                    if (!$exist) {
                        $fail('Invalid id');
                    }
                },
            ]
        ]);

        DB::beginTransaction();
        try {
            $salary = Salary::find($request->id);
            $salary->name = $request->name;
            $salary->amount = $request->amount;
            $salary->save();
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Salary updated successfully', 'salary' => $salary], 200);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function delete(Request $request) {
        $request->validate([
            'id' => [
                'required', function ($attribute, $value, $fail) {
                    $exist = Salary::find($value);
                    if (!$exist) {
                        $fail('Invalid id');
                    }
                },
            ]
        ]);

        DB::beginTransaction();
        try {
            $salary = Salary::find($request->id);
            $salary->delete();
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Salary deleted successfully'], 200);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function all() {
        $salaries = Salary::all();
        return response()->json([
            'status' => true,
            'salaries' => $salaries
        ], 200);
    }
}
