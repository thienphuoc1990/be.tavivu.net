<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('API')->middleware('cors')->group(function() {
    Route::get('/configs', 'BaseController@getConfigs');
    Route::get('/home', 'PageController@index');
    Route::get('/about', 'PageController@about');
    Route::get('/tours-in-country', 'PageController@toursInCountry');
    Route::get('/tours-international', 'PageController@toursInternational');
    Route::get('/images', 'PageController@images');
    Route::get('/contact', 'PageController@contact');

    Route::get('tours/{id}', 'TourController@view');

    Route::get('contacts/send', 'ContactController@sendContact');

    Route::get('services/send', 'ServiceController@sendService');

    Route::get('tour-order/send', 'TourController@sendTourOrder');
    Route::get('pages/{page}', 'PageController@page');

    Route::get('news', 'NewsController@index');
    Route::get('news/{id}', 'NewsController@view');
});

