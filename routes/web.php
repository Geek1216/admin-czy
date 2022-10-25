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
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('route:cache');
    $exitCode = Artisan::call('view:cache');
    //$exitCode = Artisan::call('config:cache');
    // return what you want
});

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('/', function () {
    if (is_file(storage_path('.installed'))) {
        return redirect()->route('home');
    } else {
        return redirect()->route('install.overview');
    }
});

Route::prefix('links')->group(function () {

    $redirect = function () {
        $package = request('package');
        abort_if(empty($package), 404);
        return redirect('https://play.google.com/store/apps/details?id=' . $package);
    };

    Route::get('clips', $redirect);
    Route::get('users', $redirect);
});

Route::redirect('clips', 'links/clips');

Route::get('install', 'InstallController@overview')->name('install.overview');
Route::get('install/configure', 'InstallController@configure')->name('install.configure');
Route::post('install/configure', 'InstallController@save');
Route::get('install/finalize', 'InstallController@finalize')->name('install.finalize');
Route::post('install/finalize', 'InstallController@run');

Auth::routes(['register' => false]);

Route::middleware(['auth', 'enabled', 'can:manage'])->group(function () {

    Route::get('home', 'HomeController@index')->name('home');

    Route::group(['layout' => 'layouts.panel'], function () {
        
        Route::middleware(['can:administer', 'password.confirm'])->group(function () {
            Route::livewire('settings', 'settings-update')->name('settings.update');
            Route::livewire('dotenv', 'dotenv-update')->name('dotenv.update');
        });

        Route::livewire('users', 'user-index')->name('users.index');
        Route::livewire('users/create', 'user-create')->name('users.create');
        Route::livewire('users/{user}', 'user-show')->name('users.show');
        Route::livewire('users/{user}/update', 'user-update')->name('users.update');
        Route::livewire('users/{user}/destroy', 'user-destroy')->name('users.destroy');

        Route::prefix('videos')->group(function () {

            Route::livewire('clip-sections', 'clip-section-index')->name('clip-sections.index');
            Route::livewire('clip-sections/create', 'clip-section-create')->name('clip-sections.create');
            Route::livewire('clip-sections/{section}', 'clip-section-show')->name('clip-sections.show');
            Route::livewire('clip-sections/{section}/update', 'clip-section-update')->name('clip-sections.update');
            Route::livewire('clip-sections/{section}/destroy', 'clip-section-destroy')->name('clip-sections.destroy');

            Route::livewire('clips', 'clip-index')->name('clips.index');
            Route::livewire('clips/{clip}', 'clip-show')->name('clips.show');
            Route::livewire('clips/{clip}/update', 'clip-update')->name('clips.update');
            Route::livewire('clips/{clip}/destroy', 'clip-destroy')->name('clips.destroy');
        });

        Route::prefix('news')->group(function () {

            Route::livewire('article-sections', 'article-section-index')->name('article-sections.index');
            Route::livewire('article-sections/create', 'article-section-create')->name('article-sections.create');
            Route::livewire('article-sections/{section}', 'article-section-show')->name('article-sections.show');
            Route::livewire('article-sections/{section}/update', 'article-section-update')->name('article-sections.update');
            Route::livewire('article-sections/{section}/destroy', 'article-section-destroy')->name('article-sections.destroy');

            Route::livewire('articles', 'article-index')->name('articles.index');
            Route::livewire('articles/create', 'article-create')->name('articles.create');
            Route::livewire('articles/{article}', 'article-show')->name('articles.show');
            Route::livewire('articles/{article}/update', 'article-update')->name('articles.update');
            Route::livewire('articles/{article}/destroy', 'article-destroy')->name('articles.destroy');
        });

        Route::prefix('music')->group(function () {

            Route::livewire('song-sections', 'song-section-index')->name('song-sections.index');
            Route::livewire('song-sections/create', 'song-section-create')->name('song-sections.create');
            Route::livewire('song-sections/{section}', 'song-section-show')->name('song-sections.show');
            Route::livewire('song-sections/{section}/update', 'song-section-update')->name('song-sections.update');
            Route::livewire('song-sections/{section}/destroy', 'song-section-destroy')->name('song-sections.destroy');

            Route::livewire('songs', 'song-index')->name('songs.index');
            Route::livewire('songs/create', 'song-create')->name('songs.create');
            Route::livewire('songs/{song}', 'song-show')->name('songs.show');
            Route::livewire('songs/{song}/update', 'song-update')->name('songs.update');
            Route::livewire('songs/{song}/destroy', 'song-destroy')->name('songs.destroy');
        });

        Route::prefix('engagement')->group(function () {

            Route::livewire('suggestions', 'suggestion-index')->name('suggestions.index');
            Route::livewire('suggestions/{suggestion}', 'suggestion-show')->name('suggestions.show');
            Route::livewire('suggestions/{suggestion}/update', 'suggestion-update')->name('suggestions.update');
            Route::livewire('suggestions/{suggestion}/destroy', 'suggestion-destroy')->name('suggestions.destroy');

            Route::livewire('challenges', 'challenge-index')->name('challenges.index');
            Route::livewire('challenges/create', 'challenge-create')->name('challenges.create');
            Route::livewire('challenges/{challenge}', 'challenge-show')->name('challenges.show');
            Route::livewire('challenges/{challenge}/update', 'challenge-update')->name('challenges.update');
            Route::livewire('challenges/{challenge}/destroy', 'challenge-destroy')->name('challenges.destroy');

            Route::livewire('sticker-sections', 'sticker-section-index')->name('sticker-sections.index');
            Route::livewire('sticker-sections/create', 'sticker-section-create')->name('sticker-sections.create');
            Route::livewire('sticker-sections/{section}', 'sticker-section-show')->name('sticker-sections.show');
            Route::livewire('sticker-sections/{section}/update', 'sticker-section-update')->name('sticker-sections.update');
            Route::livewire('sticker-sections/{section}/destroy', 'sticker-section-destroy')->name('sticker-sections.destroy');

            Route::livewire('stickers/{sticker}/destroy', 'sticker-destroy')->name('stickers.destroy');

            Route::livewire('comments', 'comment-index')->name('comments.index');
            Route::livewire('comments/{comment}', 'comment-show')->name('comments.show');
            Route::livewire('comments/{comment}/destroy', 'comment-destroy')->name('comments.destroy');

            Route::livewire('reports', 'report-index')->name('reports.index');
            Route::livewire('reports/{report}', 'report-show')->name('reports.show');
            Route::livewire('reports/{report}/update', 'report-update')->name('reports.update');
            Route::livewire('reports/{report}/destroy', 'report-destroy')->name('reports.destroy');
        });

        Route::prefix('rewards')->group(function () {


            Route::livewire('credits', 'credit-index')->name('credits.index');
            Route::livewire('credits/create', 'credit-create')->name('credits.create');
            Route::livewire('credits/{credit}', 'credit-show')->name('credits.show');
            Route::livewire('credits/{credit}/update', 'credit-update')->name('credits.update');
            Route::livewire('credits/{credit}/destroy', 'credit-destroy')->name('credits.destroy');

            Route::livewire('levels', 'level-index')->name('levels.index');
            Route::livewire('levels/create', 'level-create')->name('levels.create');
            Route::livewire('levels/{level}', 'level-show')->name('levels.show');
            Route::livewire('levels/{level}/update', 'level-update')->name('levels.update');
            Route::livewire('levels/{level}/destroy', 'level-destroy')->name('levels.destroy');

            Route::livewire('items', 'item-index')->name('items.index');
            Route::livewire('items/create', 'item-create')->name('items.create');
            Route::livewire('items/{item}', 'item-show')->name('items.show');
            Route::livewire('items/{item}/update', 'item-update')->name('items.update');
            Route::livewire('items/{item}/destroy', 'item-destroy')->name('items.destroy');

            Route::livewire('payments', 'payment-index')->name('payments.index');
            Route::livewire('payments/{payment}', 'payment-show')->name('payments.show');
            Route::livewire('payments/{payment}/update', 'payment-update')->name('payments.update');
            Route::livewire('payments/{payment}/destroy', 'payment-destroy')->name('payments.destroy');

            Route::livewire('redemptions', 'redemption-index')->name('redemptions.index');
            Route::livewire('redemptions/{redemption}', 'redemption-show')->name('redemptions.show');
            Route::livewire('redemptions/{redemption}/update', 'redemption-update')->name('redemptions.update');
            Route::livewire('redemptions/{redemption}/destroy', 'redemption-destroy')->name('redemptions.destroy');

            Route::livewire('verifications', 'verification-index')->name('verifications.index');
            Route::livewire('verifications/{verification}', 'verification-show')->name('verifications.show');
            Route::livewire('verifications/{verification}/update', 'verification-update')->name('verifications.update');
            Route::livewire('verifications/{verification}/destroy', 'verification-destroy')->name('verifications.destroy');
        });

        Route::prefix('marketing')->group(function () {

            Route::livewire('promotions', 'promotion-index')->name('promotions.index');
            Route::livewire('promotions/create', 'promotion-create')->name('promotions.create');
            Route::livewire('promotions/{promotion}', 'promotion-show')->name('promotions.show');
            Route::livewire('promotions/{promotion}/update', 'promotion-update')->name('promotions.update');
            Route::livewire('promotions/{promotion}/destroy', 'promotion-destroy')->name('promotions.destroy');

            Route::livewire('advertisements', 'advertisement-index')->name('advertisements.index');
            Route::livewire('advertisements/create', 'advertisement-create')->name('advertisements.create');
            Route::livewire('advertisements/{advertisement}', 'advertisement-show')->name('advertisements.show');
            Route::livewire('advertisements/{advertisement}/update', 'advertisement-update')->name('advertisements.update');
            Route::livewire('advertisements/{advertisement}/destroy', 'advertisement-destroy')->name('advertisements.destroy');
        });

        Route::prefix('notifications')->group(function () {

            Route::livewire('notification-templates', 'notification-template-index')->name('notification-templates.index');
            Route::livewire('notification-templates/create', 'notification-template-create')->name('notification-templates.create');
            Route::livewire('notification-templates/{template}', 'notification-template-show')->name('notification-templates.show');
            Route::livewire('notification-templates/{template}/update', 'notification-template-update')->name('notification-templates.update');
            Route::livewire('notification-templates/{template}/destroy', 'notification-template-destroy')->name('notification-templates.destroy');

            Route::livewire('notification-schedules', 'notification-schedule-index')->name('notification-schedules.index');
            Route::livewire('notification-schedules/create', 'notification-schedule-create')->name('notification-schedules.create');
            Route::livewire('notification-schedules/{schedule}', 'notification-schedule-show')->name('notification-schedules.show');
            Route::livewire('notification-schedules/{schedule}/update', 'notification-schedule-update')->name('notification-schedules.update');
            Route::livewire('notification-schedules/{schedule}/destroy', 'notification-schedule-destroy')->name('notification-schedules.destroy');
        });

        Route::livewire('profile', 'profile-update')->name('profile');
        Route::livewire('wallet-response', 'payment/razorpay')->name('wallet.response');
    });
});
