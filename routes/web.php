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
Route::get('/home', 'HomeController@index');
Auth::routes();
Route::get('/post/{post_slug}', [
     'post_slug' => 'post_slug',
     'uses'     => 'PostsController@show'
]);
// User-related
Route::get('/myaccount', 'UsersController@account');
Route::post('/myaccount', 'UsersController@save');
Route::post('/user/avatar', 'UsersController@avatar');

// Manage usesr
Route::get('/manage/users/list', 'UserManagerController@list');
Route::resource('/manage/users', 'UserManagerController');


//Route::patch('/posts/unhide', 'PostsController@unhide');
//Route::patch('/posts/approve', 'PostsController@approve');
//Route::resource('posts', 'PostsController');
//Route::patch('/comments/unhide', 'CommentsController@unhide');
//Route::resource('comments', 'CommentsController');
