<?php

use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
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
    Route::post('/add-post', [TodoController::class, 'addPost']);
    Route::delete('/delete-post/{id}', [TodoController::class, 'deletePost']);
    Route::put('/update-post/{id}', [TodoController::class, 'updatePost']);
});
// اختصرت السوالف الي فوق لما استخدمت Route::resource
Route::middleware(['auth'])->group(function () {
    Route::resource('todos', TodoController::class);
});


Route::get('/user/index',[UserController::class, 'index'])->name('users.index');
Route::get('/user/show/{id}',[UserController::class, 'show'])->name('users.show');

Route::get('/post/index',[PostsController::class, 'index'])->name('posts.index');
Route::get('/post/show/{id}',[PostsController::class, 'show'])->name('posts.show');

require __DIR__.'/auth.php';
