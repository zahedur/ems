<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesignationController extends Controller
{
    public function index() {
        $designations = Designation::orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'status'        =>  true,
            'designations'  =>  $designations
        ], 200);
    }

    public function save(Request $request) {
        $request->validate([
            'name'  => 'required'
        ]);

        DB::beginTransaction();
        try {
            $designation = new Designation();
            $designation->name = $request->name;
            $designation->save();
            DB::commit();
            return response()->json(['status' => true, 'message'=> 'Designation saved successfully', 'designation' => $designation], 201);
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
                    $exist = Designation::find($value);
                    if (!$exist) {
                        $fail('Invalid id');
                    }
                },
            ]
        ]);

        DB::beginTransaction();
        try {
            $designation = Designation::find($request->id);
            $designation->name = $request->name;
            $designation->save();
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Designation updated successfully', 'designation' => $designation], 200);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function delete(Request $request) {
        $request->validate([
            'id'    => [
                'required', function ($attribute, $value, $fail) {
                    $exist = Designation::find($value);
                    if (!$exist) {
                        $fail('Invalid id');
                    }
                },
            ]
        ]);

        DB::beginTransaction();
        try {
            $designation = Designation::find($request->id);
            $designation->delete();
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Designation deleted successfully'], 200);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function all() {
        $designations = Designation::all();
        return response()->json([
            'status' => true,
            'designations'   => $designations
        ], 200);
    }
}
