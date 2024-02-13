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
    
    /*Profile Mgt*/
    Route::get('bot-profiles/read/{id}','App\Http\Controllers\Voyager\BotProfileController@readProfile')->name('bot-profiles.read');
    Route::get('bot-profiles/machine/add','App\Http\Controllers\Voyager\BotProfileController@readProfile')->name('bot-profiles.read');
    /*Bot Machine Routes*/
    // Route::get('machines', 'App\Http\Controllers\Voyager\BotMachineController@index')->name('machines');
    Route::get('machines/create', 'App\Http\Controllers\Voyager\BotMachineController@create')->name('machines.create');
    Route::post('machines/store', 'App\Http\Controllers\Voyager\BotMachineController@store')->name('machines.store');
    Route::get('machines/edit/{id}', 'App\Http\Controllers\Voyager\BotMachineController@edit');
    Route::put('machines/update/{id}', 'App\Http\Controllers\Voyager\BotMachineController@update')->name('machines.update');
    Route::get('machines/read/{id}', 'App\Http\Controllers\Voyager\BotMachineController@show')->name('machines.read');
    /*Bot Clubs Routes*/ 
    Route::get('clubs-bot/create', 'App\Http\Controllers\Voyager\BotClubController@create')->name('clubs-bot.create');
    Route::post('clubs-bot/store', 'App\Http\Controllers\Voyager\BotClubController@store')->name('clubs-bot.store');
    Route::get('clubs-bot/edit/{id}', 'App\Http\Controllers\Voyager\BotClubController@edit');
    Route::put('clubs-bot/update/{id}', 'App\Http\Controllers\Voyager\BotClubController@update')->name('clubs-bot.update');
    Route::get('clubs-bot/read/{id}', 'App\Http\Controllers\Voyager\BotClubController@show')->name('clubs-bot.read');
    /*Bot Logins Routes*/ 
    Route::get('logins-bot/create', 'App\Http\Controllers\Voyager\BotLoginController@create')->name('logins-bot.create');
    Route::post('logins-bot/store', 'App\Http\Controllers\Voyager\BotLoginController@store')->name('logins-bot.store');
    Route::get('logins-bot/edit/{id}', 'App\Http\Controllers\Voyager\BotLoginController@edit');
    Route::put('logins-bot/update/{id}', 'App\Http\Controllers\Voyager\BotLoginController@update')->name('logins-bot.update');
    Route::get('logins-bot/read/{id}', 'App\Http\Controllers\Voyager\BotLoginController@show')->name('logins-bot.read');
    /*Bot Logins Routes*/ 
    Route::get('proxy-bot/create', 'App\Http\Controllers\Voyager\BotProxyController@create')->name('proxy-bot.create');
    Route::post('proxy-bot/store', 'App\Http\Controllers\Voyager\BotProxyController@store')->name('proxy-bot.store');
    Route::get('proxy-bot/edit/{id}', 'App\Http\Controllers\Voyager\BotProxyController@edit');
    Route::put('proxy-bot/update/{id}', 'App\Http\Controllers\Voyager\BotProxyController@update')->name('proxy-bot.update');
    Route::get('proxy-bot/read/{id}', 'App\Http\Controllers\Voyager\BotProxyController@show')->name('proxy-bot.read');
    /*Bot Mgt*/
    Route::get('overview','App\Http\Controllers\Voyager\BotOverviewController@index')->name('overview')->middleware('voyagerAuth:subscribe');
    Route::get('socket/start','App\Http\Controllers\Voyager\BotProfileController@startMachine')->name('socket.start');
    Route::get('socket/stop','App\Http\Controllers\Voyager\BotProfileController@stopMachine')->name('socket.stop');
    
    /*Import OnSaleNotificationData*/
    //Route::post('notification/import','App\Http\Controllers\Admin\UpCommingSale@importOnSaleNotification')->name('notification/import');
    /*Export OnSaleNotificationData*/
    //Route::get('notification/export','App\Http\Controllers\Admin\UpCommingSale@exportOnSaleNotification')->name('notification/export');
});
