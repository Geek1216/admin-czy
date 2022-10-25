<?php

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
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache Cleared";
});

Route::get('/config-cache', function() {
    Artisan::call('config:clear');
    return "Config Cleared";
});

Route::get('/view-cache', function() {
    Artisan::call('view:cache');
    return "View Cleared";
});

Route::get('/route-cache', function() {
    Artisan::call('route:cache');
    return "Route Cleared";
});

Route::get('/', function () {
    return response()->json([
        'version' => substr(config('fixtures.git_commit'), 0, 7),
    ]);
});

Route::middleware('throttle:10,1,login')->group(function () {

    Route::post('login/email', 'LoginController@email');
    Route::post('login/facebook', 'LoginController@facebook');
    Route::post('login/firebase', 'LoginController@firebase');
    Route::post('login/google', 'LoginController@google');
    Route::post('login/phone', 'LoginController@phone');
});

Route::middleware('throttle:2,1,otp')->group(function () {

    Route::post('login/email/otp', 'LoginController@emailOtp');
    Route::post('login/phone/otp', 'LoginController@phoneOtp');
});

Route::middleware('auth.optional:sanctum')->group(function () {

    Route::apiResource('articles/sections', 'ArticleSectionController')->only(['index', 'show']);
    Route::apiResource('clips/sections', 'ClipSectionController')->only(['index', 'show']);
    Route::apiResource('songs/sections', 'SongSectionController')->only(['index', 'show']);

    Route::apiResource('advertisements', 'AdvertisementController')->only('index');
    Route::apiResource('articles', 'ArticleController')->only(['index', 'show']);
    Route::apiResource('challenges', 'ChallengeController')->only('index');
    Route::apiResource('clips', 'ClipController');
    Route::apiResource('clips/{clip}/comments', 'CommentController')->only('index');
    Route::get('hashtags', 'HashtagController@index');
    Route::apiResource('promotions', 'PromotionController')->only('index');
    Route::get('songs/{song}', 'SongController@show');
    Route::get('suggestions', 'SuggestionController@index');
    Route::get('users/{username}/find', 'UserController@find');
    Route::apiResource('users', 'UserController')->only(['index', 'show']);
    Route::apiResource('users/{user}/followers', 'FollowerController')->only('index');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('users/{user}/blocked', 'BlockController')->only('store');
    Route::delete('users/{user}/blocked', 'BlockController@destroy');
    Route::apiResource('clips', 'ClipController')->only(['store', 'destroy']);
    Route::apiResource('clips/{clip}/comments', 'CommentController')->only(['store', 'destroy']);
    Route::post('clips/{clip}/likes', 'LikeController@store');
    Route::delete('clips/{clip}/likes', 'LikeController@destroy');
    Route::post('clips/{clip}/saves', 'SaveController@store');
    Route::delete('clips/{clip}/saves', 'SaveController@destroy');
    Route::apiResource('devices', 'DeviceController')->only(['store', 'update']);
    Route::get('notifications', 'NotificationController@index');
    Route::delete('notifications', 'NotificationController@destroy');
    Route::get('profile', 'ProfileController@show');
    Route::post('profile', 'ProfileController@update');
    Route::delete('profile', 'ProfileController@destroy');
    Route::delete('profile/photo', 'ProfileController@destroyPhoto');
    Route::apiResource('reports', 'ReportController')->only('store');
    Route::get('songs', 'SongController@index');
    Route::apiResource('stickers/sections', 'StickerSectionController')->only(['index', 'show']);
    Route::get('stickers', 'StickerController@index');
    Route::apiResource('threads', 'ThreadController')->only(['index', 'store', 'show']);
    Route::apiResource('threads/{thread}/messages', 'MessageController')->only(['index', 'store', 'destroy']);
    Route::apiResource('users/{user}/followers', 'FollowerController')->only('store');
    Route::delete('users/{user}/followers', 'FollowerController@destroy');
    Route::apiResource('verifications', 'VerificationController')->only('store');
});

Route::middleware('auth.optional:sanctum')->group(function () {

    Route::get('stickers/{sticker}', 'StickerController@show');
});
