<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return view('welcome');
    return view('auth.login_imagem_fundo');
})->name('imagem.fundo');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/user/index', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::get('/user/edit/{user}', [UserController::class, 'editar'])->name('user.edit');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::put('/user/update/{id}', [UserController::class, 'update'])->name('user.update');


    Route::get('/posts/search', [PostsController::class, 'search'])->name('search.posts');

        //  Route::resource('tasks', TasksController::class);
    Route::get('/dashboard', [PostsController::class, 'dashboard'])->name('dashboard');


      // definindo as rotas para posts
    Route::resource('posts', PostsController::class);
     //tela de exclusão de  posts
     Route::get('/confirm-delete-posts/{post}',[PostsController::class, 'confirm_delete_posts'])->name('confirm.delete.posts');

     Route::post('/posts/{post}/comment', [PostsController::class, 'storeComment2'])->name('posts.comment');


    // definindo as rotas para category
    Route::resource('category', CategoryController::class);
    //tela de exclusão de categoria
    Route::get('/confirm-delete-category/{category}',[CategoryController::class, 'confirm_delete_category'])->name('confirm.delete.category');


     // definindo as rotas para tag
     Route::resource('tag', TagController::class);
     //tela de exclusão de tags
     Route::get('/confirm-delete-tag/{tag}',[TagController::class, 'confirm_delete_tag'])->name('confirm.delete.tag');

     //rotas para tela principal do blog
     Route::get('/posts/category/{category}',[PostsController::class,'filterByCategory'])->name('posts.category');
     Route::get('/posts/tag/{tag}',[PostsController::class,'filterByTag'])->name('posts.tag');


    // definindo as rotas para comentarios
     Route::resource('comments', CommentsController::class);
     Route::get('/confirm-delete-comment/{comment}',[CommentsController::class, 'confirm_delete_comment'])->name('confirm.delete.comment');




     Route::get('/category/{category}', [PostsController::class, 'filterByCategory'])->name('posts.category');
     Route::get('/tag/{tag}', [PostsController::class, 'filterByTag'])->name('posts.tag');
     Route::get('/posts/search', [PostsController::class, 'filterBySearch'])->name('posts.search');

     Route::post('/upload-image', [ImageUploadController::class, 'upload'])->name('upload.image');

     Route::post('posts/{post}/like', [LikeController::class, 'likePost'])->name('posts.like');
     Route::post('posts/{post}/unlike', [LikeController::class, 'unlikePost'])->name('posts.unlike');



    });

require __DIR__.'/auth.php';
