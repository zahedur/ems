<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    public function index() {
        $leaves = Leave::orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'status'   =>  true,
            'leaves'   =>  $leaves
        ], 200);
    }

    public function save(Request $request) {
        $request->validate([
            'name'  => 'required',
            'day'   =>  'required|integer'
        ]);

        DB::beginTransaction();
        try {
            $leave = new Leave();
            $leave->name = $request->name;
            $leave->day = $request->day;
            $leave->save();
            DB::commit();
            return response()->json(['status' => true, 'message'=> 'Leave saved successfully', 'leave' => $leave], 201);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function update(Request $request) {
        $request->validate([
            'name'  => 'required',
            'day'   =>  'required|integer',
            'id'    => [
                'required', function ($attribute, $value, $fail) {
                    $exist = Leave::find($value);
                    if (!$exist) {
                        $fail('Invalid id');
                    }
                },
            ]
        ]);

        DB::beginTransaction();
        try {
            $leave = Leave::find($request->id);
            $leave->name = $request->name;
            $leave->day = $request->day;
            $leave->save();
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Leave updated successfully', 'leave' => $leave], 200);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function delete(Request $request) {
        $request->validate([
            'id'    => [
                'required', function ($attribute, $value, $fail) {
                    $exist = Leave::find($value);
                    if (!$exist) {
                        $fail('Invalid id');
                    }
                },
            ]
        ]);

        DB::beginTransaction();
        try {
            $leave = Leave::find($request->id);
            $leave->delete();
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Leave deleted successfully'], 200);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function all() {
        $leaves = Leave::all();
        return response()->json([
            'status' => true,
            'leaves' => $leaves
        ], 200);
    }
}
