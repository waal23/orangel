<?php

use App\Http\Controllers\DiamondPackController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\LiveApplicationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\RedeemRequestsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [LoginController::class, 'login']);
Route::get('index', [LoginController::class, 'index'])->middleware(['checkLogin'])->name('index');


Route::post('login', [LoginController::class, 'checklogin'])->middleware(['checkLogin'])->name('login');
Route::post('updateProflie', [LoginController::class, 'updateProflie'])->middleware(['checkLogin'])->name('updateProflie');

Route::get('logout', [LoginController::class, 'logout'])->middleware(['checkLogin'])->name('logout');


/*|--------------------------------------------------------------------------|
  | users  Route                                                           |
  |--------------------------------------------------------------------------|*/

Route::view('users', 'users')->middleware(['checkLogin'])->name('users');
Route::view('addFakeUser', 'addFakeUser')->middleware(['checkLogin'])->name('addFakeUser');

Route::post('fetchAllUsers', [UsersController::class, 'fetchAllUsers'])->middleware(['checkLogin'])->name('fetchAllUsers');
Route::post('updateUser', [UsersController::class, 'updateUser'])->middleware(['checkLogin'])->name('updateUser');
Route::post('addUserImage', [UsersController::class, 'addUserImage'])->middleware(['checkLogin'])->name('addUserImage');
Route::post('addFakeUserFromAdmin', [UsersController::class, 'addFakeUserFromAdmin'])->middleware(['checkLogin'])->name('addFakeUserFromAdmin');
Route::post('fetchStreamerUsers', [UsersController::class, 'fetchStreamerUsers'])->middleware(['checkLogin'])->name('fetchStreamerUsers');
Route::post('fetchFakeUsers', [UsersController::class, 'fetchFakeUsers'])->middleware(['checkLogin'])->name('fetchFakeUsers');

Route::get('blockUser/{id}', [UsersController::class, 'blockUser'])->middleware(['checkLogin'])->name('blockUser');
Route::get('deleteUserImage/{id}', [UsersController::class, 'deleteUserImage'])->middleware(['checkLogin'])->name('deleteUserImage');
Route::get('unblockUser/{id}', [UsersController::class, 'unblockUser'])->middleware(['checkLogin'])->name('unblockUser');
Route::get('viewUserDetails/{id}', [UsersController::class, 'viewUserDetails'])->middleware(['checkLogin'])->name('viewUserDetails');
Route::get('allowLiveToUser/{id}', [UsersController::class, 'allowLiveToUser'])->middleware(['checkLogin'])->name('allowLiveToUser');
Route::get('restrictLiveToUser/{id}', [UsersController::class, 'restrictLiveToUser'])->middleware(['checkLogin'])->name('restrictLiveToUser');

/*|--------------------------------------------------------------------------|
  | package  Route                                                           |
  |--------------------------------------------------------------------------|*/

Route::view('package', 'package')->name('package')->middleware(['checkLogin']);
Route::post('fetchAllPackage', [PackageController::class, 'fetchAllPackage'])->middleware(['checkLogin'])->name('fetchAllPackage');
Route::post('addPackage', [PackageController::class, 'addPackage'])->middleware(['checkLogin'])->name('addPackage');
Route::post('updatePackage', [PackageController::class, 'updatePackage'])->middleware(['checkLogin'])->name('updatePackage');
Route::get('getPackageById/{id}', [PackageController::class, 'getPackageById'])->middleware(['checkLogin'])->name('getPackageById');
Route::get('deletePackage/{id}', [PackageController::class, 'deletePackage'])->middleware(['checkLogin'])->name('deletePackage');



/*|--------------------------------------------------------------------------|
  | Interests Route                                                           |
  |--------------------------------------------------------------------------|*/

Route::view('interest', 'interest')->middleware(['checkLogin'])->name('interest');


Route::get('deleteInterest/{cat_id}', [InterestController::class, 'deleteInterest'])->name('deleteInterest')->middleware(['checkLogin']);


Route::post('fetchAllInterest', [InterestController::class, 'fetchAllInterest'])->middleware(['checkLogin'])->name('fetchAllInterest');
Route::post('updateInterest', [InterestController::class, 'updateInterest'])->middleware(['checkLogin'])->name('updateInterest');
Route::post('addInterest', [InterestController::class, 'addInterest'])->middleware(['checkLogin'])->name('addInterest');


/*|--------------------------------------------------------------------------|
  | Report  Route                                                           |
  |--------------------------------------------------------------------------|*/

Route::view('report', 'report')->name('report')->middleware(['checkLogin']);
Route::post('fetchAllReport', [ReportController::class, 'fetchAllReport'])->middleware(['checkLogin'])->name('fetchAllReport');

/*|--------------------------------------------------------------------------|
  | Notification  Route
  |--------------------------------------------------------------------------|*/
Route::get('notifications', [NotificationController::class, 'notifications'])->name('notifications')->middleware(['checkLogin']);
Route::post('fetchAllNotification', [NotificationController::class, 'fetchAllNotification'])->name('fetchAllNotification')->middleware(['checkLogin']);
Route::post('addNotification', [NotificationController::class, 'addNotification'])->name('addNotification')->middleware(['checkLogin']);
Route::post('updateNotification', [NotificationController::class, 'updateNotification'])->name('updateNotification')->middleware(['checkLogin']);
Route::get('deleteNotification/{id}', [NotificationController::class, 'deleteNotification'])->middleware(['checkLogin'])->name('deleteNotification');
Route::get('getNotificationById/{id}', [NotificationController::class, 'getNotificationById'])->middleware(['checkLogin'])->name('getNotificationById');



/*|--------------------------------------------------------------------------|
  | setting  Route                                                           |
  |--------------------------------------------------------------------------|*/

Route::get('setting', [SettingController::class, 'setting'])->name('setting')->middleware(['checkLogin']);
Route::post('updateAdmob', [SettingController::class, 'updateAdmob'])->middleware(['checkLogin'])->name('updateAdmob');
Route::post('updateOther', [SettingController::class, 'updateOther'])->middleware(['checkLogin'])->name('updateOther');
Route::post('updateAppdata', [SettingController::class, 'updateAppdata'])->middleware(['checkLogin'])->name('updateAppdata');

/*|--------------------------------------------------------------------------|
  | Diamond Pack  Route
  |--------------------------------------------------------------------------|*/

Route::get('diamondpacks', [DiamondPackController::class, 'diamondpacks'])->name('diamondpacks')->middleware(['checkLogin']);
Route::post('fetchDiamondPackages', [DiamondPackController::class, 'fetchDiamondPackages'])->name('fetchDiamondPackages')->middleware(['checkLogin']);
Route::post('addDiamondPack', [DiamondPackController::class, 'addDiamondPack'])->name('addDiamondPack')->middleware(['checkLogin']);
Route::post('updateDiamondPack', [DiamondPackController::class, 'updateDiamondPack'])->name('updateDiamondPack')->middleware(['checkLogin']);
Route::get('getDiamondPackById/{id}', [DiamondPackController::class, 'getDiamondPackById'])->name('getDiamondPackById')->middleware(['checkLogin']);
Route::get('deleteDiamondPack/{id}', [DiamondPackController::class, 'deleteDiamondPack'])->name('deleteDiamondPack')->middleware(['checkLogin']);

/*|--------------------------------------------------------------------------|
| Gift  Route
|--------------------------------------------------------------------------|*/

Route::get('gifts', [SettingController::class, 'gifts'])->name('gifts')->middleware(['checkLogin']);
Route::post('fetchAllGifts', [SettingController::class, 'fetchAllGifts'])->name('fetchAllGifts')->middleware(['checkLogin']);
Route::get('deleteGift/{id}', [SettingController::class, 'deleteGift'])->name('deleteGift')->middleware(['checkLogin']);
Route::post('addGift', [SettingController::class, 'addGift'])->name('addGift')->middleware(['checkLogin']);
Route::post('updateGift', [SettingController::class, 'updateGift'])->name('updateGift')->middleware(['checkLogin']);


/*|--------------------------------------------------------------------------|
  | Livestream Application  Route
  |--------------------------------------------------------------------------|*/
Route::get('liveapplication', [LiveApplicationController::class, 'liveapplication'])->name('liveapplication')->middleware(['checkLogin']);
Route::post('fetchLiveApplications', [LiveApplicationController::class, 'fetchLiveApplications'])->name('fetchLiveApplications')->middleware(['checkLogin']);
Route::post('fetchLiveHistory', [LiveApplicationController::class, 'fetchLiveHistory'])->name('fetchLiveHistory')->middleware(['checkLogin']);
Route::get('deleteLiveApplication/{id}', [LiveApplicationController::class, 'deleteLiveApplication'])->name('deleteLiveApplication')->middleware(['checkLogin']);
Route::get('approveApplication/{id}', [LiveApplicationController::class, 'approveApplication'])->name('approveApplication')->middleware(['checkLogin']);
Route::get('viewLiveApplication/{id}', [LiveApplicationController::class, 'viewLiveApplication'])->name('viewLiveApplication')->middleware(['checkLogin']);
Route::get('livehistory', [LiveApplicationController::class, 'livehistory'])->name('livehistory')->middleware(['checkLogin']);

/*|--------------------------------------------------------------------------|
  | Redeem Requests Features Route
  |--------------------------------------------------------------------------|*/
Route::get('redeemrequests', [RedeemRequestsController::class, 'redeemrequests'])->name('redeemrequests')->middleware(['checkLogin']);
Route::post('fetchPendingRedeems', [RedeemRequestsController::class, 'fetchPendingRedeems'])->name('fetchPendingRedeems')->middleware(['checkLogin']);
Route::post('fetchCompletedRedeems', [RedeemRequestsController::class, 'fetchCompletedRedeems'])->name('fetchCompletedRedeems')->middleware(['checkLogin']);
Route::post('completeRedeem', [RedeemRequestsController::class, 'completeRedeem'])->name('completeRedeem')->middleware(['checkLogin']);
Route::get('deleteRedeemRequest/{id}', [RedeemRequestsController::class, 'deleteRedeemRequest'])->name('deleteRedeemRequest')->middleware(['checkLogin']);
Route::get('getRedeemById/{id}', [RedeemRequestsController::class, 'getRedeemById'])->name('getRedeemById')->middleware(['checkLogin']);

/*|--------------------------------------------------------------------------|
| Verification Requests 
|--------------------------------------------------------------------------|*/
Route::get('verificationrequests', [UsersController::class, 'verificationrequests'])->name('verificationrequests')->middleware(['checkLogin']);
Route::post('fetchverificationRequests', [UsersController::class, 'fetchverificationRequests'])->name('fetchverificationRequests')->middleware(['checkLogin']);
Route::get('rejectVerificationRequest/{id}', [UsersController::class, 'rejectVerificationRequest'])->middleware(['checkLogin'])->name('rejectVerificationRequest');
Route::get('approveVerificationRequest/{id}', [UsersController::class, 'approveVerificationRequest'])->middleware(['checkLogin'])->name('approveVerificationRequest');

// Pages Routes
Route::get('viewPrivacy', [PagesController::class, 'viewPrivacy'])->middleware(['checkLogin'])->name('viewPrivacy');
Route::post('updatePrivacy', [PagesController::class, 'updatePrivacy'])->middleware(['checkLogin'])->name('updatePrivacy');
Route::get('viewTerms', [PagesController::class, 'viewTerms'])->middleware(['checkLogin'])->name('viewTerms');
Route::post('updateTerms', [PagesController::class, 'updateTerms'])->middleware(['checkLogin'])->name('updateTerms');
Route::get('privacypolicy', [PagesController::class, 'privacypolicy'])->name('privacypolicy');
Route::get('termsOfUse', [PagesController::class, 'termsOfUse'])->name('termsOfUse');
