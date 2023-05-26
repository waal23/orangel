<?php

use App\Http\Controllers\DiamondPackController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\LiveApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\RedeemRequestsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UsersController;
use App\Models\DiamondPacks;
use App\Models\LiveApplications;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


/*|--------------------------------------------------------------------------|
  | Users Route                                                              |
  |--------------------------------------------------------------------------|*/

Route::post('register', [UsersController::class, 'addUserDetails'])->middleware('checkHeader');
Route::post('updateProfile', [UsersController::class, 'updateProfile'])->middleware('checkHeader');
Route::post('fetchUsersByCordinates', [UsersController::class, 'fetchUsersByCordinates'])->middleware('checkHeader');
Route::post('updateUserBlockList', [UsersController::class, 'updateUserBlockList'])->middleware('checkHeader');
Route::post('deleteMyAccount', [UsersController::class, 'deleteMyAccount'])->middleware('checkHeader');

Route::post('getProfile', [UsersController::class, 'getProfile'])->middleware('checkHeader');
Route::post('getUserDetails', [UsersController::class, 'getUserDetails'])->middleware('checkHeader');
Route::post('getRandomProfile', [UsersController::class, 'getRandomProfile'])->middleware('checkHeader');
Route::post('getExplorePageProfileList', [UsersController::class, 'getExplorePageProfileList'])->middleware('checkHeader');

Route::post('updateSavedProfile', [UsersController::class, 'updateSavedProfile'])->middleware('checkHeader');
Route::post('updateLikedProfile', [UsersController::class, 'updateLikedProfile'])->middleware('checkHeader');
Route::post('notifyLikedUser', [NotificationController::class, 'notifyLikedUser'])->middleware('checkHeader');

Route::post('getPackage', [PackageController::class, 'getPackage'])->middleware('checkHeader');
Route::post('getInterests', [InterestController::class, 'getInterests'])->middleware('checkHeader');
Route::post('addReport', [ReportController::class, 'addReport'])->middleware('checkHeader');
Route::post('getSettingData', [SettingController::class, 'getSettingData'])->middleware('checkHeader');

Route::post('searchUsers', [UsersController::class, 'searchUsers'])->middleware('checkHeader');
Route::post('searchUsersForInterest', [UsersController::class, 'searchUsersForInterest'])->middleware('checkHeader');

Route::post('getUserNotifications', [NotificationController::class, 'getUserNotifications'])->middleware('checkHeader');
Route::post('getAdminNotifications', [NotificationController::class, 'getAdminNotifications'])->middleware('checkHeader');

Route::post('getDiamondPacks', [DiamondPackController::class, 'getDiamondPacks'])->middleware('checkHeader');

Route::post('onOffNotification', [UsersController::class, 'onOffNotification'])->middleware('checkHeader');
Route::post('updateLiveStatus', [UsersController::class, 'updateLiveStatus'])->middleware('checkHeader');
Route::post('onOffShowMeOnMap', [UsersController::class, 'onOffShowMeOnMap'])->middleware('checkHeader');
Route::post('onOffAnonymous', [UsersController::class, 'onOffAnonymous'])->middleware('checkHeader');
Route::post('onOffVideoCalls', [UsersController::class, 'onOffVideoCalls'])->middleware('checkHeader');
Route::post('fetchSavedProfiles', [UsersController::class, 'fetchSavedProfiles'])->middleware('checkHeader');

Route::post('applyForLive', [LiveApplicationController::class, 'applyForLive'])->middleware('checkHeader');
Route::post('applyForVerification', [UsersController::class, 'applyForVerification'])->middleware('checkHeader');

Route::post('addCoinsToWallet', [UsersController::class, 'addCoinsToWallet'])->middleware('checkHeader');
Route::post('minusCoinsFromWallet', [UsersController::class, 'minusCoinsFromWallet'])->middleware('checkHeader');
Route::post('increaseStreamCountOfUser', [UsersController::class, 'increaseStreamCountOfUser'])->middleware('checkHeader');

Route::post('addLiveStreamHistory', [LiveApplicationController::class, 'addLiveStreamHistory'])->middleware('checkHeader');
Route::post('logOutUser', [UsersController::class, 'logOutUser'])->middleware('checkHeader');
Route::post('fetchAllLiveStreamHistory', [LiveApplicationController::class, 'fetchAllLiveStreamHistory'])->middleware('checkHeader');

Route::post('placeRedeemRequest', [RedeemRequestsController::class, 'placeRedeemRequest'])->middleware('checkHeader');
Route::post('fetchMyRedeemRequests', [RedeemRequestsController::class, 'fetchMyRedeemRequests'])->middleware('checkHeader');
Route::post('storeFileGivePath', [SettingController::class, 'storeFileGivePath'])->middleware('checkHeader');




Route::get('test', [UsersController::class, 'test'])->middleware('checkHeader');
