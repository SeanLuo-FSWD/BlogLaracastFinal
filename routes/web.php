<?php

use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PostCommentsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use Illuminate\Support\Facades\Route;


Route::get('/', [PostController::class, 'index'])->name('home');

Route::get('posts/{post:slug}', [PostController::class, 'show']);
Route::post('posts/{post:slug}/comments', [PostCommentsController::class, 'store']);

// @@60 not using an array here, as it will automatically call the __invoke method in controller
Route::post('newsletter', NewsletterController::class);

//@@49 'guest' - Only a guest can access this page, if you are signed in, you won't see it. See "app/Http/Kernel.php"
Route::get('register', [RegisterController::class, 'create'])->middleware('guest');
Route::post('register', [RegisterController::class, 'store'])->middleware('guest');

Route::get('login', [SessionsController::class, 'create'])->middleware('guest');
Route::post('login', [SessionsController::class, 'store'])->middleware('guest');

//@@49 'auth' - Inverse of 'guest', so that only authenticated can access
Route::post('logout', [SessionsController::class, 'destroy'])->middleware('auth');


Route::middleware('can:admin')->group(function () {

    // @@69_12:40 resource can replace all the commented routes thanks to their standard naming
    Route::resource('admin/posts', AdminPostController::class)->except('show');

//    Route::post('admin/posts', [AdminPostController::class, 'store'])->middleware('admin');
//    Route::get('admin/posts/create', [AdminPostController::class, 'create'])->middleware('admin');
//    Route::get('admin/posts', [AdminPostController::class, 'index'])->middleware('admin');
//    Route::get('admin/posts/{post}/edit', [AdminPostController::class, 'edit'])->middleware('admin');
//    Route::patch('admin/posts/{post}', [AdminPostController::class, 'update'])->middleware('admin');
//    Route::delete('admin/posts/{post}', [AdminPostController::class, 'destroy'])->middleware('admin');
});
