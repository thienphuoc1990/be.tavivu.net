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

Route::get('/', function () {
    // return view('welcome');
    return redirect(route('admin.home'));
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/send/email', 'HomeController@mail');

Route::prefix('admin')->namespace('Admin')->group(function() {
    Route::get('/', 'HomeController@index')->name('admin.home');

    Route::get('/clear-cache', function() {
        $exitCode = Artisan::call('cache:clear', [] );
        echo $exitCode; 
    })->name('admin.clear-cache');

    Route::get('/clear-config', function() {
        $exitCode = Artisan::call('config:cache', [] );
        echo $exitCode; 
    });

    Route::get('/dump-autoload', function() {
        $exitCode = Artisan::call('dump-autoload', [] );
        echo $exitCode; 
    });

    Route::get('/storage-link', function() {
        $exitCode = Artisan::call('storage:link', [] );
        echo $exitCode; 
    });

    Route::prefix('tours')->group(function() {
        Route::get('/', 'TourController@index')->name('admin.tours.index');
        Route::get('/create', 'TourController@create')->name('admin.tours.create');
        Route::get('/{tour}/edit', 'TourController@edit')->name('admin.tours.edit');
        Route::post('/store', 'TourController@store')->name('admin.tours.store');
        Route::delete('/destroy', 'TourController@destroy')->name('admin.tours.destroy');

        Route::get('/get-places', 'TourController@getPlaces')->name('admin.tours.getPlaces');
        Route::get('/get-tours', 'TourController@getTours')->name('admin.tours.getTours');
        Route::get('/get-tours-detail', 'TourController@getTourDetails')->name('admin.tours.getTourDetails');

        Route::get('/{tour}/schedules', 'TourController@indexSchedule')->name('admin.tours.schedules.index');
        Route::get('/{tour}/schedules/add', 'TourController@addSchedule')->name('admin.tours.schedules.add');
        Route::get('/{tour}/schedules/{schedule}/edit', 'TourController@editSchedule')->name('admin.tours.schedules.edit');
        Route::post('/{tour}/schedules/store', 'TourController@storeSchedule')->name('admin.tours.schedules.store');
        Route::delete('/{tour}/schedules/destroy', 'TourController@destroySchedule')->name('admin.tours.schedules.destroy');

        Route::get('/{tour}/details', 'TourController@indexDetail')->name('admin.tours.details.index');
        Route::get('/{tour}/details/add', 'TourController@addDetail')->name('admin.tours.details.add');
        Route::get('/{tour}/details/{detail}/edit', 'TourController@editDetail')->name('admin.tours.details.edit');
        Route::post('/{tour}/details/store', 'TourController@storeDetail')->name('admin.tours.details.store');
        Route::delete('/{tour}/details/destroy', 'TourController@destroyDetail')->name('admin.tours.details.destroy');
    });

    Route::prefix('pages')->group(function () {
        Route::get('/', 'PageController@index')->name('admin.pages.index');
        Route::get('/create', 'PageController@create')->name('admin.pages.create');
        Route::get('/{page}/edit', 'PageController@edit')->name('admin.pages.edit');
        Route::post('/store', 'PageController@store')->name('admin.pages.store');
        Route::delete('/destroy', 'PageController@destroy')->name('admin.pages.destroy');
    });

    Route::prefix('places')->group(function () {
        Route::get('/', 'PlaceController@index')->name('admin.places.index');
        Route::get('/create', 'PlaceController@create')->name('admin.places.create');
        Route::get('/{place}/edit', 'PlaceController@edit')->name('admin.places.edit');
        Route::post('/store', 'PlaceController@store')->name('admin.places.store');
        Route::delete('/destroy', 'PlaceController@destroy')->name('admin.places.destroy');
    });

    Route::prefix('contacts')->group(function () {
        Route::get('/', 'ContactController@index')->name('admin.contacts.index');
        Route::get('/create', 'ContactController@create')->name('admin.contacts.create');
        Route::get('/{contact}/edit', 'ContactController@edit')->name('admin.contacts.edit');
        Route::post('/store', 'ContactController@store')->name('admin.contacts.store');
        Route::delete('/destroy', 'ContactController@destroy')->name('admin.contacts.destroy');
        Route::delete ('/delete', 'ContactController@delete')->name('admin.contacts.delete');
    });

    Route::prefix('services')->group(function () {
        Route::get('/', 'ServiceController@index')->name('admin.services.index');
        Route::get('/create', 'ServiceController@create')->name('admin.services.create');
        Route::get('/{service}/edit', 'ServiceController@edit')->name('admin.services.edit');
        Route::post('/store', 'ServiceController@store')->name('admin.services.store');
        Route::delete('/destroy', 'ServiceController@destroy')->name('admin.services.destroy');
    });

    Route::prefix('tour_orders')->group(function () {
        Route::get('/', 'TourOrderController@index')->name('admin.tour_orders.index');
        Route::get('/create', 'TourOrderController@create')->name('admin.tour_orders.create');
        Route::get('/{tour_order}/edit', 'TourOrderController@edit')->name('admin.tour_orders.edit');
        Route::post('/store', 'TourOrderController@store')->name('admin.tour_orders.store');
        Route::delete('/destroy', 'TourOrderController@destroy')->name('admin.tour_orders.destroy');
    });

    Route::prefix('images')->group(function () {
        Route::get('/', 'ImageController@index')->name('admin.images.index');
        Route::get('/create', 'ImageController@create')->name('admin.images.create');
        Route::get('/{image}/edit', 'ImageController@edit')->name('admin.images.edit');
        Route::post('/store', 'ImageController@store')->name('admin.images.store');
        Route::delete('/destroy', 'ImageController@destroy')->name('admin.images.destroy');
        Route::post('/upload', 'ImageController@upload')->name('admin.images.upload');
    });

    Route::prefix('news')->group(function () {
        Route::get('/', 'NewsController@index')->name('admin.news.index');
        Route::get('/create', 'NewsController@create')->name('admin.news.create');
        Route::get('/{page}/edit', 'NewsController@edit')->name('admin.news.edit');
        Route::post('/store', 'NewsController@store')->name('admin.news.store');
        Route::delete('/destroy', 'NewsController@destroy')->name('admin.news.destroy');
    });


});
