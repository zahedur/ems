<?php

use Illuminate\Support\Facades\DB;

function resError($message, $status) {
    return response()->json(['success' => false, 'message' => $message], $status);
}

function getNameByID($model, $id, $name) {
    $getName = DB::table($model)->find($id);
    if ($getName){
        return $getName->$name;
    }else{
        return '';
    }
}

function salariesHistory($employees){
    foreach ($employees as $employee) {
        if (count($employee->salaries)){
            $years = [];
            $total_amount = 0;
            foreach ($employee->salaries as $salary){
                $years[] = $salary->year;
                $total_amount += $salary->amount;
            }
            $unique_years = array_unique($years);

            $year_salaries = [];
            foreach ($unique_years as $unique_year){
                $year_data = [];
                foreach ($employee->salaries as $s){
                    if ($s->year == $unique_year){
                        $year_data[] = $s;
                    }
                }
                $year_salaries[$unique_year] = $year_data;
            }
            $employee->salaries_history = $year_salaries;
            $employee->total_amount = $total_amount;
        }else{
            $employee->salaries_history = [];
            $employee->total_amount = 0;
        }
    }
}


function salaryHistory($employee){
    if (count($employee->salaries)){
        $years = [];
        $total_amount = 0;
        foreach ($employee->salaries as $salary){
            $years[] = $salary->year;
            $total_amount += $salary->amount;
        }
        $unique_years = array_unique($years);

        $year_salaries = [];
        foreach ($unique_years as $unique_year){
            $year_data = [];
            foreach ($employee->salaries as $s){
                if ($s->year == $unique_year){
                    $year_data[] = $s;
                }
            }
            $year_salaries[$unique_year] = $year_data;
        }
        $employee->salaries_history = $year_salaries;
        $employee->total_amount = $total_amount;
    }else{
        $employee->salaries_history = [];
        $employee->total_amount = 0;
    }
}
