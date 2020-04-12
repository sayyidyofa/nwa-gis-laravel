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
Route::auth(['register'=>false]);
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

// Dashboard
Route::namespace('Dashboard')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::name('dashboard.')->group(function () {
            Route::get('/', 'HomeController@dashboardPage')->name('home');
            Route::get('map', 'HomeController@mapPage')->name('map');
            Route::get('/gisindex', 'HomeController@gisIndexPage')->name('gisindex');
        });
    });
});

// Model Resources
Route::namespace('Resource')->group(function () {
    Route::resources([
        'geometry' => 'GeometryController',
        'wilderness' => 'WildernessController',
        'gis' => 'GISController',
        'user' => 'UserController'
    ]);
});

// Custom Routes
Route::get('/geometry/{id}/coordinates', 'Dashboard\HomeController@showCoords');

