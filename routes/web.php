<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
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





Route::middleware(['auth'])->group(function () {
    Route::get('/add-post', [TodoController::class, 'addPost']);
    Route::get('/delete-post/{id}', [TodoController::class, 'deletePost']);
    Route::get('/update-post/{id}', [TodoController::class, 'updatePost']);
});
// اختصرت السوالف الي فوق لما استخدمت Route::resource
Route::middleware(['auth'])->group(function () {
    Route::resource('todos', TodoController::class);
});
require __DIR__.'/auth.php';
