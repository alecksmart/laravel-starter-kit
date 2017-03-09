<?php

use \App\Post;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// General display
Route::get('/', 'BlogController@index');
Auth::routes();
Route::get('/home', 'HomeController@index');

// Display single post
Route::get('/post/{post_slug}', [
     'post_slug' => 'post_slug',
     'uses'     => 'BlogController@show'
]);
// Create new blog post
Route::post('/post/create', 'BlogController@createPost');
// Create new comment
Route::post('/comment/create', 'BlogController@createComment');

// User account related
Route::get('/myaccount', 'UsersController@account');
Route::post('/myaccount', 'UsersController@save');
Route::post('/user/avatar', 'UsersController@avatar');

// Manage usesrs
Route::get('/manage/users/list', 'UserManagerController@list');
Route::resource('/manage/users', 'UserManagerController');


//Route::patch('/posts/unhide', 'PostsController@unhide');
//Route::patch('/posts/approve', 'PostsController@approve');
//Route::resource('posts', 'PostsController');
//Route::patch('/comments/unhide', 'CommentsController@unhide');
//Route::resource('comments', 'CommentsController');
