<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DepartmentController;
use App\Http\Controllers\API\DesignationController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\LeaveController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\SalaryController;
use App\Http\Controllers\API\TodayProductController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\GeneralController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RawMaterialController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

//General
Route::get('general', [GeneralController::class, 'index']);

Route::post('auth/login', [AuthController::Class, 'login']);
Route::post('auth/forgot-password', [AuthController::Class, 'forgotPassword']);
Route::post('auth/forgot-password/verify-code', [AuthController::Class, 'verifyForgotPassword']);
Route::post('auth/reset-password', [AuthController::Class, 'resetPassword']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('auth/logout', [AuthController::Class, 'logout']);
    Route::post('auth/user', [AuthController::Class, 'user']);
    Route::post('user/update', [UserController::Class, 'update']);
    Route::post('user/update-password', [UserController::Class, 'updatePassword']);


    //General
    Route::post('general/update', [GeneralController::class, 'update']);
    Route::get('general/countries', [GeneralController::class, 'countries']);
    Route::get('units', [GeneralController::class, 'Units']);

    //Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);

    //Departments
    Route::get('departments', [DepartmentController::class, 'index']);
    Route::post('departments/save', [DepartmentController::class, 'save']);
    Route::post('departments/update', [DepartmentController::class, 'update']);
    Route::post('departments/delete', [DepartmentController::class, 'delete']);
    Route::get('departments/all', [DepartmentController::class, 'all']);

    //Designations
    Route::get('designations', [DesignationController::class, 'index']);
    Route::post('designations/save', [DesignationController::class, 'save']);
    Route::post('designations/update', [DesignationController::class, 'update']);
    Route::post('designations/delete', [DesignationController::class, 'delete']);
    Route::get('designations/all', [DesignationController::class, 'all']);

    //Leaves
    Route::get('leaves', [LeaveController::class, 'index']);
    Route::post('leaves/save', [LeaveController::class, 'save']);
    Route::post('leaves/update', [LeaveController::class, 'update']);
    Route::post('leaves/delete', [LeaveController::class, 'delete']);
    Route::get('leaves/all', [LeaveController::class, 'all']);

    //Salaries
    Route::get('salaries', [SalaryController::class, 'index']);
    Route::post('salaries/save', [SalaryController::class, 'save']);
    Route::post('salaries/update', [SalaryController::class, 'update']);
    Route::post('salaries/delete', [SalaryController::class, 'delete']);
    Route::get('salaries/all', [SalaryController::class, 'all']);

    //Employees
    Route::get('employees', [EmployeeController::class, 'index']);
    Route::post('employees/save', [EmployeeController::class, 'save']);
    Route::post('employees/update', [EmployeeController::class, 'update']);
    Route::post('employees/delete', [EmployeeController::class, 'delete']);
    Route::get('employees/active-all', [EmployeeController::class, 'activeAll']);

    //Provide Salary
    Route::post('employees/add-paid-salary', [EmployeeController::class, 'addPaidSalary']);


});
