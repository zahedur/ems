<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\General;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function update(Request $request) {
        $request->validate([
            'name'              => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::find(Auth::user()->id);
            $user->name = $request->name;
            if ($request->hasFile('photo')) {

                if ($user->image && Storage::disk('local')->exists('public/users/'.$user->image)){
                    Storage::delete('public/users/'.$user->image);
                }

                $image = $request->file('photo');
                $filenameWithExt = $image->getClientOriginalName();
                $filename = Auth::user()->id.'_'.time().'_'.$filenameWithExt;
                $image->storeAs('public/users', $filename);
                $user->image = $filename;
            }
            $user->save();

            if ($user->image){
                if (Storage::disk('local')->exists('public/users/'.$user->image)){
                    $user->image = url('/').'/storage/users/'.$user->image;
                }else{
                    $user->image = null;
                }
            }
            Db::commit();
            return response()->json(['status' => true, 'user' => $user, 'message' => 'User updated successfully!'], 201);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'oldPassword'       => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Old Password didn\'t match');
                    }
                },
            ],
            'newPassword'       => 'required|min:8',
            'confirmPassword'   => 'required|same:newPassword',
        ]);

        DB::beginTransaction();
        try {
            $user = User::find(Auth::user()->id);
            $user->password = Hash::make($request->confirmPassword);
            $user->save();
            Db::commit();
            return response()->json(['status' => true, 'message' => 'Password changed successfully!'], 200);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }
}
