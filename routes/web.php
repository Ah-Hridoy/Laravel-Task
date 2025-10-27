<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [CourseController::class, 'create'])->name('index');
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');