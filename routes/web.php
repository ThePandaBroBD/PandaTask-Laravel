<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


Route::redirect('/', '/tasks');
Route::resource('tasks', TaskController::class);
Route::patch('tasks/{task}/toggle-complete', [TaskController::class, 'toggleComplete'])->name('tasks.toggle-complete');