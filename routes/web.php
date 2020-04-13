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
    Route::get('/', 'FrontController@homePage'); // Public landing page
    Route::get('/gisdata', 'FrontController@indexGIS'); // Return all GIS data in JSON
});

// Auth
Route::auth(['register'=>false]); // No user registration allowed except by Admin or Sadmin
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

// Dashboard
Route::namespace('Dashboard')->group(function () { // All Controller class in app/Http/Controllers/Dashboard
    Route::prefix('dashboard')->group(function () { // Add prefix 'dashboard' to every route url in this group. Ex: /dashboard/map
        Route::name('dashboard.')->group(function () { // Add prefix 'dashboard.' to every route name in this group. Ex: dashboard.map
            Route::get('/', 'HomeController@dashboardPage')->name('home'); // Dashboard Home
            Route::get('map', 'HomeController@mapPage')->name('map'); // Dashboard Map Page
            Route::get('/gisindex', 'HomeController@gisIndexPage')->name('gisindex'); // GIS Index Page
        });
    });
});

// Model Resources
Route::namespace('Resource')->group(function () {
    // https://stackoverflow.com/questions/23505875/laravel-routeresource-vs-routecontroller
    Route::resource('geometry', 'GeometryController',
        ['except' => ['index', 'show', 'create', 'store']]
    );
    Route::resource('wilderness', 'WildernessController',
        ['except' => ['index', 'show']]
    );
    Route::resource('gis', 'GISController',
        ['only' => ['create', 'store']]
    );
    Route::resource('user', 'UserController');
});

// Custom Routes
Route::get('/geometry/{id}/coordinates', 'Dashboard\HomeController@showCoords'); // Dumps geometry coordinates
Route::get('/wilderness/{id}/addgeometry', 'Resource\GeometryController@create');
Route::post('/wilderness/{id}/addgeometry', 'Resource\GeometryController@store');
