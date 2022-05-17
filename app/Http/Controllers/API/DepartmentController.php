<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function index() {
        $departments = Department::orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'status'        =>  true,
            'departments'   =>  $departments
        ], 200);
    }

    public function save(Request $request) {
        $request->validate([
            'name'  => 'required'
        ]);

        DB::beginTransaction();
        try {
            $department = new Department();
            $department->name = $request->name;
            $department->save();
            DB::commit();
            return response()->json(['status' => true, 'message'=> 'Department saved successfully', 'department' => $department], 201);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function update(Request $request) {
        $request->validate([
            'name'  => 'required',
            'id'    => [
                'required', function ($attribute, $value, $fail) {
                    $exist = Department::find($value);
                    if (!$exist) {
                        $fail('Invalid id');
                    }
                },
            ],
        ]);

        DB::beginTransaction();
        try {
            $department = Department::find($request->id);
            $department->name = $request->name;
            $department->save();
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Department updated successfully', 'department' => $department], 200);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function delete(Request $request) {
        $request->validate([
            'id'    => [
                'required', function ($attribute, $value, $fail) {
                    $exist = Department::find($value);
                    if (!$exist) {
                        $fail('Invalid id');
                    }
                },
            ],
        ]);

        DB::beginTransaction();
        try {
            $department = Department::find($request->id);
            $department->delete();
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Department deleted successfully'], 200);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function all() {
        $departments = Department::all();
        return response()->json([
            'status' => true,
            'departments'   => $departments
        ], 200);
    }
}
