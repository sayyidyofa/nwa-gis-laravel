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

//Route::get('/', '');

// Front
Route::namespace('Front')->group(function () {
    Route::get('/', 'FrontController@homePage');
    Route::get('/gisdata', 'FrontController@indexGIS');
});

// Auth
Route::namespace('Auth')->group(function () {
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout')->name('logout');
});

// Dashboard
Route::namespace('Admin')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::name('admin.')->group(function () {
            Route::get('/', 'AdminController@dashboardPage')->name('home');
            Route::get('map', 'AdminController@mapPage')->name('map');
        });
    });
});

// Models
Route::namespace('Resource')->group(function () {
    Route::resources([
        'geometry' => 'GeometryController',
        'wilderness' => 'WildernessController'
    ]);
    Route::get('/geometry/{id}/coordinates', 'GeometryController@showCoords');
});

// Custom Resource routes




