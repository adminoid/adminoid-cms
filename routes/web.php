<?php

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

Auth::routes();
Route::get('logout', function () {
    Auth::logout();
    return redirect('logouted');
});

Route::get('logged', function () {
    return 'you are logged';
});
Route::get('logouted', function () {
    return 'you are logouted';
});
Route::get('registered', function () {
    return 'you are registered';
});

// Admin panel
Route::middleware(['admin'])->group(function () {
    Route::get('admin-panel', 'AdminPanel\PageController@index');
    Route::post('admin-panel/tree', 'AdminPanel\PageController@tree');
    Route::post('admin-panel/get-branch', 'AdminPanel\PageController@loadBranch');
    Route::post('admin-panel/tree-move', 'AdminPanel\PageController@treeMove');
    Route::post('admin-panel/delete-page', 'AdminPanel\PageController@deletePage');
    Route::post('admin-panel/page', 'AdminPanel\PageController@page');
    Route::post('admin-panel/page/create', 'AdminPanel\PageController@createPage');
    Route::post('admin-panel/page/save', 'AdminPanel\PageController@savePage');
    Route::post('admin-panel/image-upload', 'AdminPanel\PageController@imageUpload');
});

Route::view('403.html', 'errors.403');
Route::view('503.html', 'errors.503');

Route::get('sitemap.xml', 'ExportsController@sitemap');

Route::get('{path?}', 'PagesController@show')
    ->where('path', '[a-z0-9/-]+');
