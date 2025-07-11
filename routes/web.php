<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
use App\Http\Controllers\TaskController;

// Group all task routes under auth middleware
Route::middleware(['auth'])->group(function () {
    
    // Task index: all for admin, assigned-only for user
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

    // Show create task form (admin only)
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');

    // Store new task
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // Show edit form (admin only)
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');

    // Update task
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

    // Delete task
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});
