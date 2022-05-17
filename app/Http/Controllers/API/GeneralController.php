<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\General;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GeneralController extends Controller
{
    public function index() {
        $general = General::first();

        if ($general->logo){
            if (Storage::disk('local')->exists('public/general/'.$general->logo)){
                $general->logo = url('/').'/storage/general/'.$general->logo;
            }else{
                $general->logo = null;
            }
        }

        return response()->json(['status' => true, 'general' => $general]);
    }

    public function update(Request $request) {
        $request->validate([
            'name'              => 'required|string',
            'title'             => 'required|string',
            'currency_text'     => 'required|string',
            'currency_symbol'   => 'required|string',
            'version'           => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $general = General::first();
            $general->app_name = $request->name;
            $general->title = $request->title;
            $general->description = $request->description;
            $general->currency_text = $request->currency_text;
            $general->currency_symbol = $request->currency_symbol;
            $general->version = $request->version;

            if ($request->hasFile('logo')) {

                if ($general->logo && Storage::disk('local')->exists('public/general/'.$general->logo)){
                    Storage::delete('public/general/'.$general->logo);
                }

                $image = $request->file('logo');
                $filenameWithExt = $image->getClientOriginalName();
                $filename = Auth::user()->id.'_'.time().'_'.$filenameWithExt;
                $image->storeAs('public/general', $filename);
                $general->logo = $filename;
            }

            $general->save();

            if ($general->logo){
                if (Storage::disk('local')->exists('public/general/'.$general->logo)){
                    $general->logo = url('/').'/storage/general/'.$general->logo;
                }else{
                    $general->logo = null;
                }
            }
            Db::commit();
            return response()->json(['status' => true, 'general' => $general, 'message' => 'General info updated successfully!'], 201);
        }catch (\Exception $e){
            DB::rollBack();
            return resError($e->getMessage(), 417);
        }
    }


    public function countries() {
        $countries = Country::all();
        return response()->json([
            'status' => true,
            'countries' => $countries
        ], 200);
    }
}
