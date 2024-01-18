<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\MustBeValidVoyagerAuth;

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
    //return view('voyager::login');
});

// Your overwrites here
Route::get('/', function () {
    return redirect('/admin/login');
});

Route::group(['prefix' => 'admin'], function () {
     Voyager::routes();
    /*
        Subscribe Module
    */
    Route::get('subscribe','App\Http\Controllers\Voyager\ClubController@index')->name('subscribe')->middleware('voyagerAuth:subscribe');
    Route::get('subscribe/modelListings','App\Http\Controllers\Voyager\ClubController@getSubscribeModel')->name('subscribe/modelListings');
    Route::post('subscribe/saveAssignData','App\Http\Controllers\Voyager\ClubController@saveAssignData')->name('subscribe/saveAssignData');  

    /*Notification Index Page*/
    Route::get('notification','App\Http\Controllers\Voyager\UpComingSale@index')->name('notification')->middleware('voyagerAuth:notification');
    /*Ajax Pagination*/
    Route::get('notification/ajax-pagination','App\Http\Controllers\Voyager\UpComingSale@onSaleNotificationPageAjax')->name('notification/ajax-pagination');
    /*Notification Filters*/
    Route::get('notification/filter','App\Http\Controllers\Voyager\UpComingSale@filterUpcommingSale')->name('notification/filter');
    /*Notification Date Filters*/
    Route::get('notification/dateFilter','App\Http\Controllers\Voyager\UpComingSale@filterDate')->name('notification/dateFilter');
    
    /*Bot Mgt*/
    Route::get('overview','App\Http\Controllers\Voyager\BotOverviewController@index')->name('overview')->middleware('voyagerAuth:subscribe');
    Route::get('overview/socket','App\Http\Controllers\Voyager\BotOverviewController@socket')->name('overview.socket');
    
    /*Import OnSaleNotificationData*/
    //Route::post('notification/import','App\Http\Controllers\Admin\UpCommingSale@importOnSaleNotification')->name('notification/import');
    /*Export OnSaleNotificationData*/
    //Route::get('notification/export','App\Http\Controllers\Admin\UpCommingSale@exportOnSaleNotification')->name('notification/export');
});
