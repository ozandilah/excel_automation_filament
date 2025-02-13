<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelProcessingController;

Route::get('/process-excel', [ExcelProcessingController::class, 'processExcel']);
