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



// [SS:20171119] Added the following routes for the API

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::group(['middleware' => 'auth:api'], function(){
	Route::post('details', 'API\UserController@details');
    //Sample role entry for system
    //Route::get('announcements', 'API\AnnouncementController@retrieveAnnouncements')->middleware(['role:Admin+Security+HE']);
    Route::get('announcements', 'API\AnnouncementController@retrieveAnnouncements');
    Route::post('activity/log', 'API\ActivityController@logActivity');
    Route::put('activity/cancel', 'API\ActivityController@cancelActivity');
    Route::get('activity/status', 'API\ActivityController@activityStatus');
    // Security routes
    Route::get('activity/list/{action?}', 'API\ActivityController@retrieveActivities')->middleware(['role:Admin+Security']);
});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
