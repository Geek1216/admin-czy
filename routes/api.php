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

Route::get('/route-list', function () {
    $routes = app('router')->getRoutes();
    return  $arrays = (array) $routes;
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache Cleared";
});

Route::get('/config-cache', function () {
    Artisan::call('config:clear');
    return "Config Cleared";
});

Route::get('/view-cache', function () {
    Artisan::call('view:cache');
    return "View Cleared";
});

Route::get('/route-cache', function () {
    Artisan::call('route:cache');
    return "Route Cleared";
});

Route::get('/publicpath', function () {
    return public_path();
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
    return "link created";
});

Route::get('/', function () {
    return response()->json([
        'version' => substr(config('fixtures.git_commit'), 0, 7),
    ]);
});


// Route::get('/wallet-response', function () {
//     return "link created";
// })->name('walslet.response');

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
    Route::apiResource('clips/sections', 'ClipSectionController')->only(['index', 'show']); //clips/posts

    //Added by Nikita Ahuja on 25th March, 2022
    Route::apiResource('stories/sections', 'StorySectionController')->only(['index', 'show']); //stories
    //Added by Nikita Ahuja on 25th March, 2022

    Route::apiResource('songs/sections', 'SongSectionController')->only(['index', 'show']);

    Route::apiResource('advertisements', 'AdvertisementController')->only('index');
    Route::apiResource('articles', 'ArticleController')->only(['index', 'show']);
    Route::apiResource('challenges', 'ChallengeController')->only('index');
    Route::apiResource('clips', 'ClipController'); //clips/posts
    Route::apiResource('groups', 'GroupController');
    Route::apiResource('category', 'CategoryController');
    Route::get('posts', 'ClipController@posts');
    Route::apiResource('clips/{clip}/comments', 'CommentController')->only('index'); //clips/posts

    //Added by Nikita Ahuja on 25th March, 2022
    Route::apiResource('stories', 'StoryController'); //stories
    Route::apiResource('stories/{story}/comments', 'CommentController')->only('index'); //stories
    //Added by Nikita Ahuja on 25th March, 2022

    Route::get('hashtags', 'HashtagController@index');
    Route::apiResource('promotions', 'PromotionController')->only('index');
    Route::get('songs/{song}', 'SongController@show');
    Route::get('suggestions', 'SuggestionController@index');
    Route::get('users/{username}/find', 'UserController@find');
    Route::apiResource('users', 'UserController')->only(['index', 'show']);
    Route::apiResource('users/{user}/followers', 'FollowerController')->only('index');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('wallet/balance', 'WalletController@balance');
    Route::get('wallet/gifts', 'WalletController@gifts');
    Route::post('wallet/gifts', 'WalletController@gift');
    Route::post('wallet/recharge/iab', 'WalletController@rechargeIab');
    Route::post('wallet/recharge', 'WalletController@recharge');
    Route::post('wallet/redeem', 'WalletController@redeem');
    Route::get('wallet/redemptions', 'WalletController@redemptions');

    Route::apiResource('users/{user}/blocked', 'BlockController')->only('store');
    Route::delete('users/{user}/blocked', 'BlockController@destroy');
    Route::apiResource('clips', 'ClipController')->only(['store', 'destroy']); //clips/posts
    Route::apiResource('groups', 'GroupController')->only(['store', 'destroy']);
    Route::post('groups/{id}', 'GroupController@groupUpdate');
    Route::post('groups_category/{category_id}', 'GroupController@groupListCategoryWise');
    Route::post('groups_user/{user_id}', 'GroupController@groupListUserWise');
    // Route::post('groups/{id}', 'GroupController@groupUpdate');
    // Route::post('addGroup', 'GroupController@addGroup');
    Route::apiResource('category', 'CategoryController')->only(['store', 'destroy']);
    Route::get('posts', 'ClipController@posts');
    Route::apiResource('clips/{clip}/comments', 'CommentController')->only(['store', 'destroy']); //clips/posts
    Route::post('clips/{clip}/likes', 'LikeController@store'); //clips/posts
    Route::delete('clips/{clip}/likes', 'LikeController@destroy'); //clips/posts
    Route::post('clips/{clip}/saves', 'SaveController@store'); //clips/posts
    Route::delete('clips/{clip}/saves', 'SaveController@destroy'); //clips/posts
    Route::get('credits', 'CreditController@index');
    Route::get('items', 'ItemController@index');
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

    //Added by Nikita Ahuja on 25th March, 2022
    Route::apiResource('stories', 'StoryController')->only(['store', 'destroy']); //stories
    Route::apiResource('stories/{story}/comments', 'CommentController')->only(['store', 'destroy']); //stories
    Route::post('stories/{story}/likes', 'LikeController@store'); //stories
    Route::delete('stories/{story}/likes', 'LikeController@destroy'); //stories
    Route::post('stories/{story}/saves', 'SaveController@store'); //stories
    Route::delete('stories/{story}/saves', 'SaveController@destroy'); //stories
    //Added by Nikita Ahuja on 25th March, 2022
});

Route::middleware('auth.optional:sanctum')->group(function () {

    Route::get('stickers/{sticker}', 'StickerController@show');
});
