<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
 * Admin routes
 */
Route::group(['prefix' => 'admin','middleware' => ['auth']], function () {
    Route::get('/', 'App\Http\Controllers\Backend\DashboardController@index')->name('admin.dashboard');
    Route::resource('users', 'App\Http\Controllers\Backend\UsersController', ['names' => 'admin.users']);
    Route::get('/upload-media', 'App\Http\Controllers\Backend\FileController@index')->name('admin.media');
    Route::post('/upload-media', 'App\Http\Controllers\Backend\FileController@store')->name('admin.file.store');
Route::post('media', 'App\Http\Controllers\Backend\FileController@storeMedia')->name('dropzone.storeMedia');
Route::post('callback', 'App\Http\Controllers\Backend\FileController@callback')->name('drive.callback');
Route::resource('category', 'App\Http\Controllers\Backend\CategoryController');
Route::get('view-category-files/{id}', 'App\Http\Controllers\Backend\FileController@viewCategory')->name('admin.category.files');;
Route::post('sub-categories', 'App\Http\Controllers\Backend\CategoryController@getSubCategories')->name('admin.category.sub');
Route::get('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('admin.logout');
Route::get('/delete-file/{id}', 'App\Http\Controllers\Backend\FileController@delete')->name('admin.file.delete');

});
