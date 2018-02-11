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
//Sample role entry for system
//Route::get('announcements', 'API\AnnouncementController@retrieveAnnouncements')->middleware(['role:Admin+Security+HE']);

// Routes not requiring authentication
Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::get('register/confirm/{validationToken?}', 'API\UserController@confirmRegistration');

Route::post('password/reset', 'API\PasswordController@resetPassword');
Route::get('password/confirm/{confirmationToken?}', 'API\PasswordController@confirmReset');
Route::group(['middleware' => 'auth:api'], function(){
    // Basic logged in user routes
    Route::put('password/change', 'API\PasswordController@changePassword');
    Route::post('details', 'API\UserController@details');
    Route::post('activity/log', 'API\ActivityController@logActivity');
    Route::put('activity/cancel', 'API\ActivityController@cancelActivity');
    Route::get('activity/status', 'API\ActivityController@activityStatus');
    // Contact support routes
    Route::post('contact', 'API\ContactController@messageContact');
    // Security only routes
    Route::get('activity/list/{action?}', 'API\ActivityController@retrieveActivities')->middleware(['role:Admin+Security']);
    Route::put('activity/accept', 'API\ActivityController@acceptActivity')->middleware(['role:Admin+Security']);
    Route::put('activity/clear', 'API\ActivityController@clearActivity')->middleware(['role:Admin+Security']);
    // Announcement related routes
    Route::get('announcements', 'API\AnnouncementController@retrieveAnnouncements');
    Route::post('announcement/submit/admin', 'API\AnnouncementController@submitAnnouncement')->middleware(['role:Admin'])->name('Admin');
    Route::post('announcement/submit/security', 'API\AnnouncementController@submitAnnouncement')->middleware(['role:Admin+Security'])->name('Security');
    Route::post('announcement/submit/he', 'API\AnnouncementController@submitAnnouncement')->middleware(['role:Admin+HE'])->name('HE');
    Route::post('announcement/submit/general', 'API\AnnouncementController@submitAnnouncement')->middleware(['role:Admin+General'])->name('General');
    Route::post('announcement/submit/library', 'API\AnnouncementController@submitAnnouncement')->middleware(['role:Admin+Library'])->name('Library');
    // Admin CRUD related routes
    Route::resource('users', 'UserCRUDController')->middleware(['role:Admin']);
});
