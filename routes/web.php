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

Route::get('/', 'BlogController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/post/{post_slug}', [
     'post_slug' => 'post_slug',
     'uses'     => 'PostsController@show'
]);
Route::patch('/posts/unhide', 'PostsController@unhide');
Route::patch('/posts/approve', 'PostsController@approve');
Route::resource('posts', 'PostsController');
Route::patch('/comments/unhide', 'CommentsController@unhide');
Route::resource('comments', 'CommentsController');
Route::get('/myaccount', 'UsersController@account');
Route::post('/myaccount', 'UsersController@save');
Route::post('/user/avatar', 'UsersController@avatar');
